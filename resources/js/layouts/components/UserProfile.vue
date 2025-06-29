<script setup>
import avatar1 from "@images/avatars/avatar-1.png";
// import { useCookie } from '#app'

// 🔹 Récupérer les cookies
const userDataCookie = useCookie("userData");
const userRolesCookie = useCookie("userRoles");

// 🔹 Parser les données
const userData = userDataCookie.value;
const userRoles = userRolesCookie.value;

console.log("Nom:", userData.name);
console.log("Rôles:", userRoles);
</script>

<template>
  <VBadge
    dot
    location="bottom right"
    offset-x="3"
    offset-y="3"
    bordered
    color="success"
  >
    <VAvatar class="cursor-pointer" color="primary" variant="tonal">
      <VImg :src="avatar1" />

      <!-- Menu -->
      <VMenu activator="parent" width="230" location="bottom end" offset="14px">
        <VList>
          <!-- Avatar + Nom + Rôle -->
          <VListItem>
            <template #prepend>
              <VListItemAction start>
                <VBadge
                  dot
                  location="bottom right"
                  offset-x="3"
                  offset-y="3"
                  color="success"
                >
                  <VAvatar color="primary" variant="tonal">
                    <VImg :src="avatar1" />
                  </VAvatar>
                </VBadge>
              </VListItemAction>
            </template>

            <VListItemTitle class="font-weight-semibold">
              {{ userData.name }}
            </VListItemTitle>
            <VListItemSubtitle>
              {{ userRoles }}
            </VListItemSubtitle>
          </VListItem>

          <VDivider class="my-2" />

          <!-- Logout -->
          <VListItem to="/login">
            <template #prepend>
              <VIcon class="me-2" icon="tabler-logout" size="22" />
            </template>
            <VListItemTitle>Logout</VListItemTitle>
          </VListItem>
        </VList>
      </VMenu>
    </VAvatar>
  </VBadge>
</template>
