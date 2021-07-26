<template>
  <div class="post_item my-2 mx-3">
    <div class="post_header row">
      <div class="col-auto mr-auto">
        <span class="align-middle">随机头像：{{ post_data.random_head }}</span>
      </div>
      <div class="col-auto">
        <b-button
          size="sm"
          variant="warning"
          v-if="admin_status"
          @click="ban_cookie_click_admin"
        >
          碎饼
        </b-button>
        <b-button
          size="sm"
          variant="warning"
          v-if="admin_status"
          @click="post_delect_click_admin"
        >
          管删
        </b-button>
        <b-button
          size="sm"
          variant="light"
          v-if="post_data.is_your_post"
          @click="post_delect_click"
        >
          删除
        </b-button>
        <b-button
          size="sm"
          variant="info"
          v-if="!post_data.is_your_post"
          @click="reward_click"
          >打赏</b-button
        >
        <b-button size="sm" variant="success" @click="quote_click">
          回复
        </b-button>
      </div>
    </div>
    <div class="post_content my-2" style="min-height: 80px" ref="post_centent">
      <span
        v-html="post_data.content.replace(/\n/g, '<br>')"
        style="word-wrap: break-word; white-space: normal"
      ></span>
    </div>
    <div class="post_footer" ref="post_author_info">
      <span class="post_footer_text">№{{ post_data.floor }} ☆☆☆</span>
      <span class="post_nick_name">{{ post_data.nickname }}</span>
      <span class="post_footer_text">于</span>
      <span class="post_created_at">{{ post_data.created_at }}</span>
      <span class="post_footer_text"> 留言 ☆☆☆</span>
      <span v-if="thread_anti_jingfen" class="post_anti_jingfen">
        →{{ post_data.created_binggan_hash.slice(0, 5) }}
      </span>
    </div>
    <div>
      <b-modal ref="reward_modal" id="reward_modal">
        <template v-slot:modal-header>
          <h5>打赏olo 给№{{ post_data.floor }}楼</h5>
        </template>
        <template v-slot:default>
          <p>
            友情提示：您在打赏回贴作者以后，将会扣除7%的手续费。
            <br />
            例如您打赏1000块奥利奥，则对方将获得
            <span style="color: red">930 </span>块奥利奥。
          </p>
          <b-input-group prepend="打赏：">
            <b-form-input
              v-model="coin_reward_input"
              placeholder="olo数量"
            ></b-form-input>
          </b-input-group>
          <b-input-group prepend="留言：">
            <b-form-input v-model="content_reward_input"></b-form-input>
          </b-input-group>
        </template>
        <template v-slot:modal-footer="{ cancel }">
          <b-button-group>
            <b-button
              variant="success"
              :disabled="reward_handling"
              @click="reward_handle"
              >打赏！</b-button
            >
            <b-button variant="outline-secondary" @click="cancel()">
              取消
            </b-button>
          </b-button-group>
        </template>
      </b-modal>
    </div>
  </div>
</template>


<script>
import { mapState } from "vuex";
export default {
  components: {},
  props: {
    post_data: Object,
    binggan_hash: String,
    thread_anti_jingfen: Number,
  },
  data: function () {
    return {
      name: "post_item",
      content_reward_input: "",
      coin_reward_input: "",
      reward_handling: false,
    };
  },
  computed: {
    ...mapState({
      admin_status: (state) => state.User.AdminStatus,
    }),
  },
  methods: {
    reward_click() {
      this.$refs["reward_modal"].show();
    },
    reward_handle() {
      this.reward_handling = true;
      const config = {
        method: "post",
        url: "/api/user/reward",
        data: {
          binggan: this.$store.state.User.Binggan,
          forum_id: this.post_data.forum_id,
          thread_id: this.post_data.thread_id,
          post_id: this.post_data.id,
          content: this.content_reward_input,
          coin: this.coin_reward_input,
          post_floor_message: this.$refs.post_author_info.innerText,
        },
      };
      axios(config)
        .then((response) => {
          if (response.data.code == 200) {
            this.$bvToast.toast(response.data.message, {
              title: "Done.",
              autoHideDelay: 1500,
              appendToast: true,
            });
            this.$refs["reward_modal"].hide();
            this.reward_handling = false;
            this.$parent.get_posts_data();
          } else {
            this.reward_handling = false;
            alert(response.data.message);
          }
        })
        .catch((error) => {
          alert(error);
          this.reward_handling = false;
        }); // Todo:写异常返回代码
    },
    post_delect_click() {
      var isdelete = confirm("要删除这个回复吗？（消费300奥利奥）");
      if (isdelete == true) {
        const config = {
          method: "delete",
          url: "/api/posts/" + this.post_data.id,
          data: {
            binggan: this.$store.state.User.Binggan,
            thread_id: this.post_data.thread_id,
          },
        };
        axios(config)
          .then((response) => {
            if (response.data.code == 200) {
              alert("帖子删除成功");
              this.$emit("get_posts_data");
            } else {
              alert(response.data.message);
            }
          })
          .catch((error) => alert(error));
      }
    },
    post_delect_click_admin() {},
    ban_cookie_click_admin() {},
    quote_click() {
      const max_quote = 3; //最大可引用的层数
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
};
</script>

<style lang="scss">
.post_footer_text {
  color: #5fb878;
}
.post_footer {
  font-size: calc(0.8rem + 0.3vw);
}
.post_item {
  border-bottom: 1px solid #111;
}
.emoji_img {
  max-height: 67px;
  max-width: 67px;
}
.quote_content {
  color: #777777;
}
</style>
