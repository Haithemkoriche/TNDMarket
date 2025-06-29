<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";

const orders = ref([]);
const loading = ref(false);
const statuses = ["en_attente", "confirme", "livre"];
    const accessToken = useCookie("accessToken").value;

const headers = [
  { title: "ID", key: "id" },
  { title: "Product", key: "product.title" },
  { title: "Buyer", key: "buyer.name" },
  { title: "Quantity", key: "quantity" },
  { title: "Total Price", key: "total_price" },
  { title: "Status", key: "status" },
  { title: "Actions", key: "actions" },
];

const fetchOrders = async () => {
  loading.value = true;
  try {
    const { data } = await axios.get("/api/orders", {
      headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json", // 👈 Force JSON response
      },
    });
    orders.value = data;
  } catch (error) {
    console.error(error);
  } finally {
    loading.value = false;
  }
};

const updateStatus = async (orderId, status) => {
  try {
    await axios.put(`/api/orders/${orderId}/status`, {
      status,
      headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json", // 👈 Force JSON response
      },
    });
    fetchOrders();
  } catch (error) {
    console.error(error);
  }
};

const getStatusColor = (status) => {
  switch (status) {
    case "en_attente":
      return "warning";
    case "confirme":
      return "info";
    case "livre":
      return "success";
    default:
      return "secondary";
  }
};

onMounted(() => {
  fetchOrders();
});
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardTitle>Orders</VCardTitle>
        <VCardText>
          <VDataTable :headers="headers" :items="orders" :loading="loading">
            <template #[`item.total_price`]="{ item }">
              {{ item.total_price }} €
            </template>

            <template #[`item.status`]="{ item }">
              <VChip :color="getStatusColor(item.status)">
                {{ item.status }}
              </VChip>
            </template>

            <template #[`item.actions`]="{ item }">
              <VMenu>
                <template #activator="{ props }">
                  <VBtn icon size="small" v-bind="props">
                    <VIcon icon="tabler-dots-vertical" />
                  </VBtn>
                </template>

                <VList>
                  <VListItem
                    v-for="status in statuses"
                    :key="status"
                    @click="updateStatus(item.id, status)"
                  >
                    <VListItemTitle>{{ status }}</VListItemTitle>
                  </VListItem>
                </VList>
              </VMenu>
            </template>
          </VDataTable>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
