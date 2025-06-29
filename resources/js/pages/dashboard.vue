<script setup>
import { useTheme } from "vuetify";
import { hexToRgb } from "@layouts/utils";
import axios from "axios";
import { ofetch } from "ofetch";
import { onMounted, ref } from "vue";

const vuetifyTheme = useTheme();
const accessToken = useCookie("accessToken").value;

const statisticsVertical = ref([
  {
    title: "Users",
    stats: "0",
    icon: "tabler-users",
    color: "primary",
  },
  {
    title: "Centers",
    stats: "0",
    icon: "tabler-building-hospital",
    color: "success",
  },
  {
    title: "Products",
    stats: "0",
    icon: "tabler-shopping-cart",
    color: "warning",
  },
  {
    title: "Orders",
    stats: "0",
    icon: "tabler-file-invoice",
    color: "info",
  },
]);

const activities = ref([]);

const fetchStats = async () => {
  try {
    const accessToken = useCookie("accessToken").value;
    if (!accessToken) {
      console.error("Access token is not available");
      return;
    }
    const { data } = await axios.get("/api/admin/stats", {
      headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json", // 👈 Force JSON response
      },
    });
    statisticsVertical.value[0].stats = data.total_users;
    statisticsVertical.value[1].stats = data.total_centres;
    statisticsVertical.value[2].stats = data.total_products;
    statisticsVertical.value[3].stats = data.total_orders;
  } catch (error) {
    console.error(error);
  }
};

const fetchActivities = async () => {
  try {
    const accessToken = useCookie("accessToken").value;
    if (!accessToken) {
      console.error("Access token is missing.");
      return;
    }

    const { data } = await axios.get("/api/admin/activities", {
      headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json", // 👈 Force JSON response
      },
    });

    activities.value = data;
  } catch (error) {
    console.error(
      "Failed to fetch activities:",
      error.response?.data || error.message
    );
  }
};

onMounted(() => {
  fetchStats();
  fetchActivities();
});
</script>

<template>
  <VRow>
    <!-- 👉 Statistics -->
    <VCol
      v-for="statistic in statisticsVertical"
      :key="statistic.title"
      cols="12"
      sm="6"
      lg="3"
    >
      <CardStatisticsVertical v-bind="statistic" />
    </VCol>

    <!-- 👉 Recent Activities -->
    <VCol cols="12">
      <VCard
        title="Recent Activities"
        subtitle="List of recent activities in the system"
      >
        <VCardText>
          <VTimeline
            side="end"
            align="start"
            truncate-line="both"
            density="compact"
          >
            <VTimelineItem
              v-for="activity in activities"
              :key="activity.created_at"
              dot-color="primary"
              size="x-small"
            >
              <div class="d-flex justify-space-between align-center flex-wrap">
                <span class="app-timeline-title">
                  {{ activity.message }}
                </span>
                <span class="app-timeline-meta">
                  {{ new Date(activity.created_at).toLocaleString() }}
                </span>
              </div>
            </VTimelineItem>
          </VTimeline>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
