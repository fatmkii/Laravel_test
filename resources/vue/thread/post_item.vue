
<template>
  <div class="post_item my-2 mx-3">
    <div class="post_header row">
      <div class="col-auto mr-auto">
        <span class="align-middle">随机头像：{{ post_data.random_head }}</span>
      </div>
      <div class="col-auto">
        <b-button size="sm" variant="info">打赏</b-button>
        <b-button size="sm" variant="success">回复</b-button>
      </div>
    </div>
    <div class="post_content my-2" style="min-height: 80px">
      <span v-html="post_data.content"></span>
    </div>
    <div class="post_footer">
      <span class="post_footer_text">№{{ post_data.floor }}☆☆☆</span>
      <span class="post_nick_name">{{ post_data.nickname }}</span>
      <span class="post_footer_text">于</span>
      <span class="post_created_at">{{ post_data.created_at }}</span
      ><span class="post_footer_text">留言☆☆☆</span>
    </div>
  </div>
</template>


<script>
export default {
  components: {},
  props: {
    post_data: "",
  },
  data: function () {
    return {
      name: "post_item",
    };
  },
  methods: {},
  created() {},
};
</script>

<style lang="scss" scoped>
.post_footer_text {
  color: #5fb878;
}
.post_item {
  border-bottom: 1px solid #111;
}
</style>
