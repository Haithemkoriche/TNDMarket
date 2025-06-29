<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";

const forms = ref([]);
const formTypes = ref([]);
const loading = ref(false);
const dialog = ref(false);
const currentForm = ref({
  form_type_id: null,
  patient_name: "",
  patient_surname: "",
  form_data: {},
});

const headers = [
  { title: "ID", key: "id" },
  { title: "Patient", key: "patient_name" },
  { title: "Type", key: "form_type.name" },
  { title: "Date", key: "created_at" },
  { title: "Actions", key: "actions" },
];
const accessToken = useCookie("accessToken").value;

const fetchForms = async () => {
  loading.value = true;
  try {
    const { data } = await axios.get("/api/forms", {
      headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json", // 👈 Force JSON response
      },
    });
    forms.value = data;
  } catch (error) {
    console.error(error);
  } finally {
    loading.value = false;
  }
};

const fetchFormTypes = async () => {
  try {
    const { data } = await axios.get("/api/form-types", {
      headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json", // 👈 Force JSON response
      },
    });
    formTypes.value = data;
  } catch (error) {
    console.error(error);
  }
};

const openDialog = () => {
  currentForm.value = {
    form_type_id: null,
    patient_name: "",
    patient_surname: "",
    form_data: {},
  };
  dialog.value = true;
};

const submitForm = async () => {
  try {
    await axios.post("/api/forms", currentForm.value, {
      headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json", // 👈 Force JSON response
      },
    });
    dialog.value = false;
    fetchForms();
  } catch (error) {
    console.error(error);
  }
};

const generatePdf = async (formId) => {
  try {
    const response = await axios.get(`/api/forms/${formId}/pdf`, {
      responseType: "blob",
      headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/pdf", // 👈 ici tu attends du PDF
      },
    });
    const url = window.URL.createObjectURL(
      new Blob([response.data], { type: "application/pdf" })
    );
    const link = document.createElement("a");
    link.href = url;
    link.setAttribute("download", `form_${formId}.pdf`);
    document.body.appendChild(link);
    link.click();
    window.URL.revokeObjectURL(url);
  
    window.open(url);
  } catch (error) {
      console.error("PDF generation error:", error.response || error.message || error);

  }
};

onMounted(() => {
  fetchForms();
  fetchFormTypes();
});
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardTitle>
          <VBtn color="primary" @click="openDialog"> New Form </VBtn>
        </VCardTitle>

        <VCardText>
          <VDataTable :headers="headers" :items="forms" :loading="loading">
            <template #[`item.actions`]="{ item }">
              <VBtn
                icon
                size="small"
                color="primary"
                variant="text"
                @click="generatePdf(item.id)"
              >
                <VIcon icon="tabler-file-download" />
              </VBtn>
            </template>
          </VDataTable>
        </VCardText>
      </VCard>
    </VCol>

    <VDialog v-model="dialog" max-width="800">
      <VCard>
        <VCardTitle>Create New Form</VCardTitle>
        <VCardText>
          <VForm @submit.prevent="submitForm">
            <VSelect
              v-model="currentForm.form_type_id"
              :items="formTypes"
              item-title="name"
              item-value="id"
              label="Form Type"
              class="mb-4"
            />

            <VRow>
              <VCol cols="12" md="6">
                <AppTextField
                  v-model="currentForm.patient_name"
                  label="Patient Name"
                />
              </VCol>
              <VCol cols="12" md="6">
                <AppTextField
                  v-model="currentForm.patient_surname"
                  label="Patient Surname"
                />
              </VCol>
            </VRow>

            <VCard v-if="currentForm.form_type_id" class="mb-4">
              <VCardTitle>Form Fields</VCardTitle>
              <VCardText>
                <VRow>
                  <VCol
                    v-for="field in formTypes.find(
                      (t) => t.id === currentForm.form_type_id
                    )?.fields || []"
                    :key="field.name"
                    cols="12"
                  >
                    <AppTextField
                      v-if="field.type === 'text' || field.type === 'number'"
                      v-model="currentForm.form_data[field.name]"
                      :label="field.label"
                      :type="field.type === 'number' ? 'number' : 'text'"
                    />
                    <AppTextarea
                      v-else-if="field.type === 'textarea'"
                      v-model="currentForm.form_data[field.name]"
                      :label="field.label"
                    />
                  </VCol>
                </VRow>
              </VCardText>
            </VCard>

            <VBtn type="submit" color="primary"> Submit </VBtn>
          </VForm>
        </VCardText>
      </VCard>
    </VDialog>
  </VRow>
</template>
