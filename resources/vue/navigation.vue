<template>
  <div>
    <p>{{ navi_title }}</p>
    <b-nav align="left">
      <b-nav-item>{{ navi_title }}</b-nav-item>
      <b-nav-item>板块首页</b-nav-item>
      <b-nav-item>皮肤切换</b-nav-item>
      <b-nav-item>个人中心</b-nav-item>
    </b-nav>
  </div>
</template>


<script>
export default {
  data: function () {
    return {
      name: "navigation",
      navi_title: "test",
    };
  },
};
</script>