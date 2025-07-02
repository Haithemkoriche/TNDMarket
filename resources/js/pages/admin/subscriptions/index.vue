<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";

const subscriptions = ref([]);
const centres = ref([]);
const loading = ref(false);
const dialog = ref(false);
const currentSubscription = ref({
  centre_id: null,
  status: "active",
  start_date: null,
  end_date: null,
  price: 0,
});
    const accessToken = useCookie("accessToken").value;

const statusOptions = [
  { title: "Active", value: "active" },
  { title: "Suspended", value: "suspended" },
  { title: "Cancelled", value: "cancelled" },
];

const headers = [
  { title: "ID", key: "id" },
  { title: "Centre", key: "centre.name" },
  { title: "Status", key: "status" },
  { title: "Start Date", key: "start_date" },
  { title: "End Date", key: "end_date" },
  { title: "Price", key: "price" },
  { title: "Actions", key: "actions" },
];

const fetchSubscriptions = async () => {
  loading.value = true;
  try {
    const { data } = await axios.get("/api/admin/subscriptions", {
      headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json", // 👈 Force JSON response
      },
    });
    subscriptions.value = data;
  } catch (error) {
    console.error(error);
  } finally {
    loading.value = false;
  }
};

const fetchCentres = async () => {
  try {
    const { data } = await axios.get("/api/admin/centres", {
      headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json", // 👈 Force JSON response
      },
    });
    centres.value = data;
  } catch (error) {
    console.error(error);
  }
};

const openDialog = () => {
  currentSubscription.value = {
    centre_id: null,
    status: "active",
    start_date: null,
    end_date: null,
    price: 0,
  };
  dialog.value = true;
};

const editSubscription = (subscription) => {
  currentSubscription.value = { ...subscription };
  dialog.value = true;
};

const submitSubscription = async () => {
  try {
    if (currentSubscription.value.id) {
      await axios.put(
        `/api/admin/subscriptions/${currentSubscription.value.id}`,
        {
          ...
        currentSubscription.value,
        headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json",
        }
        }
      );

    } else {
      await axios.post("/api/admin/subscriptions", currentSubscription.value, {
      headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json", // 👈 Force JSON response
      },
    });
    }
    dialog.value = false;
    fetchSubscriptions();
  } catch (error) {
    console.error(error);
  }
};

const deleteSubscription = async (subscriptionId) => {
  try {
    await axios.delete(`/api/admin/subscriptions/${subscriptionId}`, {
      headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json", // 👈 Force JSON response
      },
    });
    fetchSubscriptions();
  } catch (error) {
    console.error(error);
  }
};

const getStatusColor = (status) => {
  switch (status) {
    case "active":
      return "success";
    case "suspended":
      return "warning";
    case "cancelled":
      return "error";
    default:
      return "secondary";
  }
};

onMounted(() => {
  fetchSubscriptions();
  fetchCentres();
});
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardTitle>
          <VBtn color="primary" @click="openDialog"> Add Subscription </VBtn>
        </VCardTitle>

        <VCardText>
          <VDataTable
            :headers="headers"
            :items="subscriptions"
            :loading="loading"
          >
            <template #[`item.status`]="{ item }">
              <VChip :color="getStatusColor(item.status)">
                {{ item.status }}
              </VChip>
            </template>

            <template #[`item.price`]="{ item }"> {{ item.price }} DZD </template>

            <template #[`item.actions`]="{ item }">
              <VBtn
                icon
                size="small"
                color="primary"
                variant="text"
                @click="editSubscription(item)"
              >
                <VIcon icon="tabler-edit" />
              </VBtn>
              <VBtn
                icon
                size="small"
                color="error"
                variant="text"
                @click="deleteSubscription(item.id)"
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
          >{{
            currentSubscription.id ? "Edit" : "Add"
          }}
          Subscription</VCardTitle
        >
        <VCardText>
          <VForm @submit.prevent="submitSubscription">
            <VSelect
              v-model="currentSubscription.centre_id"
              :items="centres"
              item-title="name"
              item-value="id"
              label="Centre"
              class="mb-4"
            />

            <VSelect
              v-model="currentSubscription.status"
              :items="statusOptions"
              label="Status"
              class="mb-4"
            />

            <VRow class="mb-4">
              <VCol cols="12" md="6">
                <AppTextField
                  v-model="currentSubscription.start_date"
                  label="Start Date"
                  type="date"
                />
              </VCol>
              <VCol cols="12" md="6">
                <AppTextField
                  v-model="currentSubscription.end_date"
                  label="End Date"
                  type="date"
                />
              </VCol>
            </VRow>

            <AppTextField
              v-model="currentSubscription.price"
              label="Price"
              type="number"
              min="0"
              step="0.01"
              class="mb-4"
            />

            <VBtn type="submit" color="primary"> Save </VBtn>
          </VForm>
        </VCardText>
      </VCard>
    </VDialog>
  </VRow>
</template>
