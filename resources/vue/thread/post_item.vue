
<template>
  <div class="post_item my-2 mx-3">
    <div class="post_header row">
      <div class="col-auto mr-auto">
        <span class="align-middle">随机头像：{{ post_data.random_head }}</span>
      </div>
      <div class="col-auto">
        <b-button size="sm" variant="info">打赏</b-button>
        <b-button size="sm" variant="success" @click="quote_click">
          回复
        </b-button>
      </div>
    </div>
    <div class="post_content my-2" style="min-height: 80px" ref="post_centent">
      <span v-html="post_data.content.replace(/\n/g, '<br>')"></span>
    </div>
    <div class="post_footer" ref="post_author_info">
      <span class="post_footer_text">№{{ post_data.floor }} ☆☆☆</span>
      <span class="post_nick_name">{{ post_data.nickname }}</span>
      <span class="post_footer_text">于</span>
      <span class="post_created_at">{{ post_data.created_at }}</span
      ><span class="post_footer_text"> 留言 ☆☆☆</span>
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
  methods: {
    quote_click() {
      const max_quote = 3;  //最大可引用的层数
      var post_lines = this.$refs.post_centent.innerText.split("\n");
      var index_array = [];
      //搜索引用的层数
      post_lines.forEach((post_line, index) => {
        if (post_line.indexOf("留言 ☆☆☆") > -1) {
          index_array.push(index);
        }
      });
      //如果层数过多，只截取部分回复引用
      if (index_array.length >= max_quote) {
        post_lines = post_lines.slice(
          index_array[index_array.length - max_quote] + 1,
          post_lines.length
        );
      }
      const quote_content =
        "<span class='quote_content'>" +
        post_lines.join("\n") +
        "\n" +
        this.$refs.post_author_info.innerText +
        "</span>" +
        "\n";
      return this.$emit("quote_click", quote_content);
    },
  },
  computed: {
    content() {
      return "We are proud Microsoft to announce that Microsoft has ".replace(
        /Microsoft/g,
        "W3School"
      );
    },
  },
};
</script>

<style lang="scss">
.post_footer_text {
  color: #5fb878;
}
.post_item {
  border-bottom: 1px solid #111;
}
.emoji_img {
  max-height: 67px;
  max-width: 67px;
}
.quote_content{
  color: #777777;
}
</style>
