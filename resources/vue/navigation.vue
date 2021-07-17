<template>
  <div>
    <b-navbar>
      <b-navbar-nav>
        <b-navbar-brand href="/"> 岛2.0开发中 </b-navbar-brand>
        <b-nav-item-dropdown text="皮肤更换" right>
          <b-dropdown-item href="#">Default</b-dropdown-item>
          <b-dropdown-item href="#">Pink!</b-dropdown-item>
          <b-dropdown-item href="#">NGA</b-dropdown-item>
          <b-dropdown-item href="#">Night</b-dropdown-item>
        </b-nav-item-dropdown>
      </b-navbar-nav>
      <b-navbar-nav class="ml-auto">
        <b-button
          v-if="login_status"
          size="md"
          class="my-1 my-sm-0"
          variant="outline-dark"
          to="/user-center"
          >个人中心</b-button
        >
        <b-button
          v-else
          size="md"
          class="my-1 my-sm-0"
          variant="outline-success"
          to="/login"
          >导入饼干</b-button
        >
      </b-navbar-nav>
    </b-navbar>
  </div>
</template>


<script>
import { mapState } from "vuex";

export default {
  data: function () {
    return {
      name: "navigation",
    };
  },
  computed: mapState({
    login_status: (state) => state.User.LoginStatus,
  }),
  methods: {},
};
</script>

<style lang="sass" scoped>
nav
  background-color: #eee
  a
    color: #444
</style>