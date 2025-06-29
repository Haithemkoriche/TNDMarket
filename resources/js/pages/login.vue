<!-- resources/js/pages/login.vue -->
<script setup>
import { useGenerateImageVariant } from "@core/composable/useGenerateImageVariant";
import authV2LoginIllustrationBorderedDark from "@images/pages/auth-v2-login-illustration-bordered-dark.png";
import authV2LoginIllustrationBorderedLight from "@images/pages/auth-v2-login-illustration-bordered-light.png";
import authV2LoginIllustrationDark from "@images/pages/auth-v2-login-illustration-dark.png";
import authV2LoginIllustrationLight from "@images/pages/auth-v2-login-illustration-light.png";
import authV2MaskDark from "@images/pages/misc-mask-dark.png";
import authV2MaskLight from "@images/pages/misc-mask-light.png";
import { VNodeRenderer } from "@layouts/components/VNodeRenderer";
import { themeConfig } from "@themeConfig";
import axios from "axios";
import { useRouter } from "vue-router";

definePage({
  meta: {
    layout: "blank",
    public: true,
  },
});

const router = useRouter();

const form = ref({
  email: "",
  password: "",
  remember: false,
});

const isPasswordVisible = ref(false);
const errorMessage = ref("");
const loading = ref(false);

const authThemeImg = useGenerateImageVariant(
  authV2LoginIllustrationLight,
  authV2LoginIllustrationDark,
  authV2LoginIllustrationBorderedLight,
  authV2LoginIllustrationBorderedDark,
  true
);
const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark);

const login = async () => {
  loading.value = true;
  errorMessage.value = "";
  try {
    const { data } = await axios.post("/api/login", {
      email: form.value.email,
      password: form.value.password,
    });

    const { access_token, user } = data;

    // Store authentication details
    const accessTokenCookie = useCookie("accessToken");
    const userDataCookie = useCookie("userData");
    const userRolesCookie = useCookie("userRoles");
    const userPermissionsCookie = useCookie("userPermissions");

    accessTokenCookie.value = access_token;
    userDataCookie.value = JSON.stringify(user);
    userRolesCookie.value = JSON.stringify(user.role || []);
    userPermissionsCookie.value = JSON.stringify(user.permissions || []);

    // Optional: also store in localStorage (not recommended if SSR)
    localStorage.setItem("userRole", user.role);
    localStorage.setItem("accessToken", access_token);

    router.push("/dashboard");
  } catch (error) {
    errorMessage.value = error.response?.data?.message || "Login failed";
  } finally {
    loading.value = false;
  }
};
</script>

<template>
  <a href="javascript:void(0)">
    <div class="auth-logo d-flex align-center gap-x-3">
      <VNodeRenderer :nodes="themeConfig.app.logo" />
      <h1 class="auth-title">
        {{ themeConfig.app.title }}
      </h1>
    </div>
  </a>

  <VRow no-gutters class="auth-wrapper bg-surface">
    <VCol md="8" class="d-none d-md-flex">
      <div class="position-relative bg-background w-100 me-0">
        <div
          class="d-flex align-center justify-center w-100 h-100"
          style="padding-inline: 6.25rem"
        >
          <VImg
            max-width="613"
            :src="authThemeImg"
            class="auth-illustration mt-16 mb-2"
          />
        </div>

        <img
          class="auth-footer-mask flip-in-rtl"
          :src="authThemeMask"
          alt="auth-footer-mask"
          height="280"
          width="100"
        />
      </div>
    </VCol>

    <VCol
      cols="12"
      md="4"
      class="auth-card-v2 d-flex align-center justify-center"
    >
      <VCard flat :max-width="500" class="mt-12 mt-sm-0 pa-6">
        <VCardText>
          <h4 class="text-h4 mb-1">
            Welcome to
            <span class="text-capitalize">{{ themeConfig.app.title }}</span
            >! 👋🏻
          </h4>
          <p class="mb-0">Please sign-in to your account</p>
        </VCardText>
        <VCardText>
          <VForm @submit.prevent="login">
            <VRow>
              <VCol cols="12">
                <VAlert
                  v-if="errorMessage"
                  type="error"
                  variant="tonal"
                  class="mb-4"
                >
                  {{ errorMessage }}
                </VAlert>
              </VCol>

              <!-- email -->
              <VCol cols="12">
                <AppTextField
                  v-model="form.email"
                  autofocus
                  label="Email"
                  type="email"
                  placeholder="admin@example.com"
                />
              </VCol>

              <!-- password -->
              <VCol cols="12">
                <AppTextField
                  v-model="form.password"
                  label="Password"
                  placeholder="············"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="
                    isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'
                  "
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />

                <div
                  class="d-flex align-center flex-wrap justify-space-between my-6"
                >
                  <VCheckbox v-model="form.remember" label="Remember me" />
                </div>

                <VBtn block type="submit" :loading="loading"> Login </VBtn>
              </VCol>

              <!-- create account -->
              <VCol cols="12" class="text-body-1 text-center">
                <span class="d-inline-block"> Don't have an account? </span>
                <RouterLink
                  class="text-primary ms-1 d-inline-block text-body-1"
                  to="/register"
                >
                  Create account
                </RouterLink>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth";
</style>
