<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";

const products = ref([]);
const loading = ref(false);
const dialog = ref(false);
const currentProduct = ref({
  title: "",
  description: "",
  price: 0,
  stock: 0,
  photo: null,
});
const imageFile = ref(null);

const headers = [
  { title: "ID", key: "id" },
  { title: "Title", key: "title" },
  { title: "Price", key: "price" },
  { title: "Stock", key: "stock" },
  { title: "Photo", key: "photo" },
  { title: "Actions", key: "actions" },
];
const accessToken = useCookie("accessToken").value;
const userRolesCookie = useCookie("userRoles").value;
console.log(userRolesCookie);

const fetchProducts = async () => {
  loading.value = true;
  try {
    const { data } = await axios.get("/api/products", {
      headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json", // 👈 Force JSON response
      },
    });
    products.value = data;
  } catch (error) {
    console.error(error);
  } finally {
    loading.value = false;
  }
};

const openDialog = () => {
  currentProduct.value = {
    title: "",
    description: "",
    price: 0,
    stock: 0,
    photo: null,
  };
  imageFile.value = null;
  dialog.value = true;
};

const editProduct = (product) => {
  currentProduct.value = { ...product };
  dialog.value = true;
};

const submitProduct = async () => {
  try {
    const formData = new FormData();
    formData.append("title", currentProduct.value.title);
    formData.append("description", currentProduct.value.description);
    formData.append("price", currentProduct.value.price);
    formData.append("stock", currentProduct.value.stock);
    if (imageFile.value) {
      formData.append("photo", imageFile.value);
    }
    const accessToken = useCookie("accessToken").value;
    console.log(accessToken);
    if (currentProduct.value.id) {
      formData.append("_method", "PUT"); // <-- 👈 Ajoute cette ligne pour simuler un PUT
      await axios.post(`/api/products/${currentProduct.value.id}`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
          Authorization: `Bearer ${accessToken}`,
        },
      });
    } else {
      await axios.post("/api/products", formData, {
        headers: {
          "Content-Type": "multipart/form-data",
          Authorization: `Bearer ${accessToken}`,
        },
      });
    }

    dialog.value = false;
    fetchProducts();
  } catch (error) {
    console.error(error);
  }
};

const deleteProduct = async (productId) => {
  try {
    await axios.delete(`/api/products/${productId}`, {
      headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json", // 👈 Force JSON response
      },
    });
    fetchProducts();
  } catch (error) {
    console.error(error);
  }
};

onMounted(() => {
  fetchProducts();
});
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardTitle>
          <VBtn color="primary" @click="openDialog"> Add Product </VBtn>
        </VCardTitle>

        <VCardText>
          <VDataTable :headers="headers" :items="products" :loading="loading">
            <template #[`item.price`]="{ item }">
              {{ item.price }} DZD
            </template>

            <template #[`item.photo`]="{ item }">
              <VImg
                v-if="item.photo"
                :src="`/storage/${item.photo}`"
                width="50"
                height="50"
                cover
              />
            </template>

            <template #[`item.actions`]="{ item }">
              <VBtn
                icon
                size="small"
                color="primary"
                variant="text"
                @click="editProduct(item)"
              >
                <VIcon icon="tabler-edit" />
              </VBtn>
              <VBtn
                icon
                size="small"
                color="error"
                variant="text"
                @click="deleteProduct(item.id)"
              >
                <VIcon icon="tabler-trash" />
              </VBtn>
            </template>
          </VDataTable>
        </VCardText>
      </VCard>
    </VCol>

    <VDialog v-model="dialog" max-width="800">
      <VCard>
        <VCardTitle
          >{{ currentProduct.id ? "Edit" : "Add" }} Product</VCardTitle
        >
        <VCardText>
          <VForm @submit.prevent="submitProduct">
            <AppTextField
              v-model="currentProduct.title"
              label="Title"
              class="mb-4"
            />

            <AppTextarea
              v-model="currentProduct.description"
              label="Description"
              class="mb-4"
            />

            <VRow class="mb-4">
              <VCol cols="12" md="6">
                <AppTextField
                  v-model="currentProduct.price"
                  label="Price"
                  type="number"
                  min="0"
                  step="0.01"
                />
              </VCol>
              <VCol cols="12" md="6">
                <AppTextField
                  v-model="currentProduct.stock"
                  label="Stock"
                  type="number"
                  min="0"
                />
              </VCol>
            </VRow>

            <VFileInput
              v-model="imageFile"
              label="Product Image"
              accept="image/*"
              prepend-icon="tabler-camera"
              class="mb-4"
            />

            <VBtn type="submit" color="primary"> Save </VBtn>
          </VForm>
        </VCardText>
      </VCard>
    </VDialog>
  </VRow>
</template>
