<template>
  <div>
    <b-card title="测试中" header-tag="header" class="mt-4">
      <template v-slot:header>
        <h4 class="mb-0">公告栏</h4>
      </template>
      <b-card-text>
        发帖、回帖、表情包、Roll点、打赏、日清等各基本功能已做好。欢迎测试。<br />
        Bug可能较多，请在公告中反馈。<br />
        因系统还不稳定，随时可能需要清空数据库，请了解。
      </b-card-text>
    </b-card>
  </div>
</template>


<script>
export default {
  components: {},
  props: {},
  data: function () {
    return {
      name: "homepage_bulletin",
    };
  },
  methods: {},
};
</script>