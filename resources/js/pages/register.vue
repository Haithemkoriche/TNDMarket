<!-- resources/js/pages/register.vue -->
<script setup>
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant'
import authV2LoginIllustrationBorderedDark from '@images/pages/auth-v2-login-illustration-bordered-dark.png'
import authV2LoginIllustrationBorderedLight from '@images/pages/auth-v2-login-illustration-bordered-light.png'
import authV2LoginIllustrationDark from '@images/pages/auth-v2-login-illustration-dark.png'
import authV2LoginIllustrationLight from '@images/pages/auth-v2-login-illustration-light.png'
import authV2MaskDark from '@images/pages/misc-mask-dark.png'
import authV2MaskLight from '@images/pages/misc-mask-light.png'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import axios from 'axios'
import { useRouter } from 'vue-router'

definePage({
  meta: {
    layout: 'blank',
    public: true,
  },
})

const router = useRouter()

const form = ref({
  name: '',
  email: '',
  password: '',
  role: 'user',
  address: '',
  phone: '',
  description: ''
})

const errorMessage = ref('')
const loading = ref(false)

const authThemeImg = useGenerateImageVariant(authV2LoginIllustrationLight, authV2LoginIllustrationDark, authV2LoginIllustrationBorderedLight, authV2LoginIllustrationBorderedDark, true)
const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark)

const register = async () => {
  loading.value = true
  errorMessage.value = ''
  try {
    const payload = {
      name: form.value.name,
      email: form.value.email,
      password: form.value.password,
      role: form.value.role
    }

    if (form.value.role === 'centre') {
      payload.address = form.value.address
      payload.phone = form.value.phone
      payload.description = form.value.description
    }

    await axios.post('/api/register', payload)
    router.push('/login')
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Registration failed'
  } finally {
    loading.value = false
  }
}
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

  <VRow
    no-gutters
    class="auth-wrapper bg-surface"
  >
    <VCol
      md="8"
      class="d-none d-md-flex"
    >
      <div class="position-relative bg-background w-100 me-0">
        <div
          class="d-flex align-center justify-center w-100 h-100"
          style="padding-inline: 6.25rem;"
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
        >
      </div>
    </VCol>

    <VCol
      cols="12"
      md="4"
      class="auth-card-v2 d-flex align-center justify-center"
    >
      <VCard
        flat
        :max-width="500"
        class="mt-12 mt-sm-0 pa-6"
      >
        <VCardText>
          <h4 class="text-h4 mb-1">
            Register to <span class="text-capitalize">{{ themeConfig.app.title }}</span> 🚀
          </h4>
          <p class="mb-0">
            Make your app management easy and fun!
          </p>
        </VCardText>
        <VCardText>
          <VForm @submit.prevent="register">
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

              <VCol cols="12">
                <VRadioGroup
                  v-model="form.role"
                  inline
                >
                  <VRadio
                    label="User"
                    value="user"
                  />
                  <VRadio
                    label="Center"
                    value="centre"
                  />
                </VRadioGroup>
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="form.name"
                  label="Full Name"
                  placeholder="John Doe"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="form.email"
                  label="Email"
                  type="email"
                  placeholder="johndoe@email.com"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="form.password"
                  label="Password"
                  type="password"
                  placeholder="············"
                />
              </VCol>

              <template v-if="form.role === 'centre'">
                <VCol cols="12">
                  <AppTextField
                    v-model="form.address"
                    label="Address"
                    placeholder="Enter your address"
                  />
                </VCol>

                <VCol cols="12">
                  <AppTextField
                    v-model="form.phone"
                    label="Phone"
                    placeholder="Enter your phone"
                  />
                </VCol>

                <VCol cols="12">
                  <AppTextarea
                    v-model="form.description"
                    label="Description"
                    placeholder="Enter center description"
                  />
                </VCol>
              </template>

              <VCol cols="12">
                <VBtn
                  block
                  type="submit"
                  :loading="loading"
                >
                  Register
                </VBtn>
              </VCol>

              <VCol
                cols="12"
                class="text-body-1 text-center"
              >
                <span class="d-inline-block">
                  Already have an account?
                </span>
                <RouterLink
                  class="text-primary ms-1 d-inline-block text-body-1"
                  to="/login"
                >
                  Login
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
