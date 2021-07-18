
<template>
  <div class="overflow-auto">
    <b-pagination-nav
      v-model="currentPage"
      :number-of-pages="threads_last_page"
      limit="5"
      :base-url="base_url"
      use-router
      first-number
      last-number
      align="center"
    ></b-pagination-nav>
  </div>
</template>


<script>
import { mapState, mapGetters } from "vuex";

export default {
  components: {},
  props: {
    forum_id: Number,
  },
  data: function () {
    return {
      name: "forum_paginator",
      currentPage: Number,
    };
  },
  computed: {
    threads_last_page() {
      return this.$store.getters.ThreadsLastPage;
    },
    base_url() {
      return "/forum/" + this.forum_id + "/";
    },
  },
};
</script>