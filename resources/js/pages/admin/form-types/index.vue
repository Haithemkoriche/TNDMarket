<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";

const formTypes = ref([]);
const loading = ref(false);
const dialog = ref(false);
const currentFormType = ref({
  name: "",
  description: "",
  fields: [],
  is_active: true,
});
 const accessToken = useCookie("accessToken").value;
    // if (!accessToken) {
    //   console.error("Access token is not available");
    //   return;
    // }
const fieldTypes = [
  { title: "Text", value: "text" },
  { title: "Number", value: "number" },
  { title: "Textarea", value: "textarea" },
];

const headers = [
  { title: "ID", key: "id" },
  { title: "Name", key: "name" },
  { title: "Description", key: "description" },
  { title: "Active", key: "is_active" },
  { title: "Actions", key: "actions" },
];

const fetchFormTypes = async () => {
  loading.value = true;
  try {
    const accessToken = useCookie("accessToken").value;
    if (!accessToken) {
      console.error("Access token is not available");
      return;
    }
    const { data } = await axios.get("/api/admin/form-types", {
      headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json", // 👈 Force JSON response
      },
    });
    formTypes.value = data;
  } catch (error) {
    console.error(error);
  } finally {
    loading.value = false;
  }
};

const openDialog = () => {
  currentFormType.value = {
    name: "",
    description: "",
    fields: [],
    is_active: true,
  };
  dialog.value = true;
};

const editFormType = (formType) => {
  currentFormType.value = { ...formType };
  dialog.value = true;
};

const submitFormType = async () => {
  try {
    if (currentFormType.value.id) {
      await axios.put(
        `/api/admin/form-types/${currentFormType.value.id}`,
        currentFormType.value,
        {
          headers: {
            Authorization: `Bearer ${accessToken}`,
            Accept: "application/json", // 👈 Force JSON response
          },
        }
      );
    } else {
      await axios.post("/api/admin/form-types", currentFormType.value, {
        headers: {
          Authorization: `Bearer ${accessToken}`,
          Accept: "application/json", // 👈 Force JSON response
        },
      });
    }
    dialog.value = false;
    fetchFormTypes();
  } catch (error) {
    console.error(error);
  }
};

const deleteFormType = async (formTypeId) => {
  try {
    await axios.delete(`/api/admin/form-types/${formTypeId}`, {
      headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json", // 👈 Force JSON response
      },
    });
    fetchFormTypes();
  } catch (error) {
    console.error(error);
  }
};

const toggleActive = async (formType) => {
  try {
    await axios.put(`/api/admin/form-types/${formType.id}`, {
      is_active: formType.is_active, 
      headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json", // 👈 Force JSON response
      },
    });
  } catch (error) {
    console.error(error);
  }
};

const addField = () => {
  currentFormType.value.fields.push({
    name: "",
    type: "text",
    label: "",
  });
};

const removeField = (index) => {
  currentFormType.value.fields.splice(index, 1);
};

onMounted(() => {
  fetchFormTypes();
});
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardTitle>
          <VBtn color="primary" @click="openDialog"> Add Form Type </VBtn>
        </VCardTitle>

        <VCardText>
          <VDataTable :headers="headers" :items="formTypes" :loading="loading">
            <template #[`item.is_active`]="{ item }">
              <VSwitch v-model="item.is_active" @change="toggleActive(item)" />
            </template>

            <template #[`item.actions`]="{ item }">
              <VBtn
                icon
                size="small"
                color="primary"
                variant="text"
                @click="editFormType(item)"
              >
                <VIcon icon="tabler-edit" />
              </VBtn>
              <VBtn
                icon
                size="small"
                color="error"
                variant="text"
                @click="deleteFormType(item.id)"
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
          >{{ currentFormType.id ? "Edit" : "Add" }} Form Type</VCardTitle
        >
        <VCardText>
          <VForm @submit.prevent="submitFormType">
            <AppTextField
              v-model="currentFormType.name"
              label="Name"
              class="mb-4"
            />

            <AppTextarea
              v-model="currentFormType.description"
              label="Description"
              class="mb-4"
            />

            <VSwitch
              v-model="currentFormType.is_active"
              label="Active"
              class="mb-4"
            />

            <VCard class="mb-4">
              <VCardTitle>Fields</VCardTitle>
              <VCardText>
                <VRow
                  v-for="(field, index) in currentFormType.fields"
                  :key="index"
                  class="mb-4"
                >
                  <VCol cols="12" md="4">
                    <AppTextField v-model="field.name" label="Field Name" />
                  </VCol>
                  <VCol cols="12" md="4">
                    <VSelect
                      v-model="field.type"
                      :items="fieldTypes"
                      label="Field Type"
                    />
                  </VCol>
                  <VCol cols="12" md="3">
                    <AppTextField v-model="field.label" label="Field Label" />
                  </VCol>
                  <VCol cols="12" md="1">
                    <VBtn
                      icon
                      color="error"
                      variant="text"
                      @click="removeField(index)"
                    >
                      <VIcon icon="tabler-trash" />
                    </VBtn>
                  </VCol>
                </VRow>

                <VBtn @click="addField" color="primary">
                  <VIcon icon="tabler-plus" start />
                  Add Field
                </VBtn>
              </VCardText>
            </VCard>

            <VBtn type="submit" color="primary"> Save </VBtn>
          </VForm>
        </VCardText>
      </VCard>
    </VDialog>
  </VRow>
</template>
