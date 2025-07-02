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
  { title: "Buyer", key: "buyer_info" }, // Changed to show both types of buyers
  { title: "Quantity", key: "quantity" },
  { title: "Total Price", key: "total_price" },
  { title: "Status", key: "status" },
  { title: "Date", key: "created_at" }, // Added order date
  { title: "Actions", key: "actions" },
];

const fetchOrders = async () => {
  loading.value = true;
  try {
    const { data } = await axios.get("/api/orders", {
      headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json",
      },
    });
    orders.value = data;
  } catch (error) {
    console.error("Error fetching orders:", error.response?.data || error.message);
  } finally {
    loading.value = false;
  }
};

const updateStatus = async (orderId, status) => {
  try {
    await axios.put(
      `/api/orders/${orderId}/status`,
      { status },
      {
        headers: {
          Authorization: `Bearer ${accessToken}`,
          Accept: "application/json",
        },
      }
    );
    await fetchOrders(); // Refresh the list after update
  } catch (error) {
    console.error("Error updating status:", error.response?.data || error.message);
  }
};

const getStatusColor = (status) => {
  switch (status) {
    case "en_attente": return "warning";
    case "confirme": return "info";
    case "livre": return "success";
    default: return "secondary";
  }
};

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString();
};

onMounted(() => {
  fetchOrders();
});
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardTitle>Orders Management</VCardTitle>
        <VCardText>
          <VDataTable 
            :headers="headers" 
            :items="orders" 
            :loading="loading"
            :items-per-page="10"
          >
            <!-- Product Column -->
            <template #[`item.product.title`]="{ item }">
              <RouterLink 
                v-if="item.product"
                :to="`/products/${item.product.id}`"
                class="text-primary"
              >
                {{ item.product.title }}
              </RouterLink>
              <span v-else>Product deleted</span>
            </template>

            <!-- Buyer Information Column -->
            <template #[`item.buyer_info`]="{ item }">
              <div v-if="item.buyer">
                <div class="font-weight-medium">{{ item.buyer.name }}</div>
                <div class="text-caption text-grey">Registered user</div>
              </div>
              <div v-else-if="item.guest_buyer">
                <div class="font-weight-medium">{{ item.guest_buyer.full_name }}</div>
                <div class="text-caption text-grey">
                  {{ item.guest_buyer.email }} • {{ item.guest_buyer.wilaya }}
                </div>
                <div class="text-caption text-grey">
                  {{ item.guest_buyer.phone }}
                </div>
              </div>
              <span v-else>Unknown buyer</span>
            </template>

            <!-- Price Column -->
            <template #[`item.total_price`]="{ item }">
              {{ new Intl.NumberFormat('fr-DZ').format(item.total_price) }} DZD
            </template>

            <!-- Status Column -->
            <template #[`item.status`]="{ item }">
              <VChip :color="getStatusColor(item.status)" size="small">
                {{ item.status.replace('_', ' ') }}
              </VChip>
            </template>

            <!-- Date Column -->
            <template #[`item.created_at`]="{ item }">
              {{ formatDate(item.created_at) }}
            </template>

            <!-- Actions Column -->
            <template #[`item.actions`]="{ item }">
              <VMenu>
                <template #activator="{ props }">
                  <VBtn icon size="small" v-bind="props">
                    <VIcon icon="tabler-dots-vertical" />
                  </VBtn>
                </template>

                <VList density="compact">
                  <VListItem
                    v-for="status in statuses"
                    :key="status"
                    :value="status"
                    @click="updateStatus(item.id, status)"
                  >
                    <VListItemTitle>
                      <VChip :color="getStatusColor(status)" size="small" class="mr-2">
                        &nbsp;
                      </VChip>
                      {{ status.replace('_', ' ') }}
                    </VListItemTitle>
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

<style scoped>
.text-grey {
  color: rgba(var(--v-theme-grey-darken-1));
}
</style>
