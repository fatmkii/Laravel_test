
<template>
  <div>这是版面：{{ forum_name }}</div>
</template>


<script>
import { mapState } from "vuex";

export default {
  components: {},
  props: {
    id: "",
  },
  data: function () {
    return {
      name: "forum",
      forum_data:''
      // forum_name: "",
      // forum_count: "",
    };
  },
  computed: {
    forum_name() {
      var forum_data = this.$store.getters.ForumData(this.id);
      if (forum_data) {
        return forum_data.name;
      } else {
        return "加载中……";
      }
    },
    forum_count() {
      return this.$store.getters.ForumCount;
    },
  },
  mounted: function () {
    // this.forum_name = this.$store.getters.ForumData(this.id).name;
    // this.forum_count = this.$store.getters.ForumCount;
  },
};
</script>