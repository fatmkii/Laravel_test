
<template>
  <div>
    <p>这是个用户页面</p>
    <p>你的饼干：{{ binggan }}</p>
  </div>
</template>


<script>
import store from "../public/store";

export default {
  components: {},
  props: {},
  data: function () {
    return {
      name: "user_center",
      binggan: store.state.binggan,
    };
  },
  mounted: function () {},
};
</script>