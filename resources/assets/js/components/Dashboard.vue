<template>
  <div>
    <template v-if="checkToken()">
      <dashboard-header></dashboard-header>
      <dashboard-sidebar></dashboard-sidebar>
      <section id="main-content">
        <section class="wrapper">
          <router-view></router-view>
        </section>
      </section>
    </template>
    <template v-else>
      <router-view></router-view>
    </template>
  </div>
</template>

<script>
import DashboardHeader from "./common/Header";
import DashboardSidebar from "./common/Sidebar";

import helpers from '../mixins/helpers';
export default {
  name: "dashboard",
  components: { DashboardHeader, DashboardSidebar },
  mixins: [helpers],
  mounted() {
    console.log("Component mounted.");
  }
};
</script>
