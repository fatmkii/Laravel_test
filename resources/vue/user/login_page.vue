<template>
  <div>
    <Homepage></Homepage>
    <LoginModal></LoginModal>
  </div>
</template>


<script>
import Homepage from "../homepage/homepage.vue";
import LoginModal from "./login_modal.vue";

export default {
  components: { Homepage, LoginModal },
  props: {},
  data: function () {
    return {
      name: "login_page",
    };
  },
  mounted: function () {},
  methods: {},
};
</script>