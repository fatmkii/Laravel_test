<template>
  <div class="post_item my-2" :id="'f_' + post_data.floor">
    <div class="float-right" v-if="this.$store.state.User.LoginStatus">
      <b-button
        size="sm"
        variant="warning"
        v-if="this.$store.state.User.AdminForums.includes(this.forum_id)"
        v-show="admin_button_show"
        @click="ban_cookie_click_admin"
      >
        碎饼
      </b-button>
      <b-button
        size="sm"
        variant="warning"
        v-if="this.$store.state.User.AdminForums.includes(this.forum_id)"
        v-show="admin_button_show"
        @click="lock_cookie_click_admin"
      >
        封禁
      </b-button>
      <b-button
        size="sm"
        variant="warning"
        v-if="this.$store.state.User.AdminForums.includes(this.forum_id)"
        v-show="admin_button_show"
        @click="post_delete_all_click_admin"
      >
        删全
      </b-button>
      <b-button
        size="sm"
        variant="warning"
        v-if="
          this.$store.state.User.AdminForums.includes(this.forum_id) &&
          post_data.is_deleted == 0
        "
        v-show="admin_button_show"
        @click="post_delete_click_admin"
      >
        删帖
      </b-button>
      <b-button
        size="sm"
        variant="warning"
        v-if="
          this.$store.state.User.AdminForums.includes(this.forum_id) &&
          post_data.is_deleted != 0
        "
        v-show="admin_button_show"
        @click="post_delete_recover_click_admin"
      >
        恢复
      </b-button>
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="24"
        height="24"
        fill="currentColor"
        class="svg-light bi bi-trash"
        viewBox="0 0 16 16"
        v-if="post_data.is_your_post && post_data.is_deleted == 0"
        @click="post_delete_click"
      >
        <!-- 删除按钮 -->
        <path
          d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"
        />
        <path
          fill-rule="evenodd"
          d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"
        />
      </svg>
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="24"
        height="24"
        fill="currentColor"
        class="svg-light bi bi-recycle"
        viewBox="0 0 16 16"
        v-if="post_data.is_your_post && post_data.is_deleted == 1"
        @click="post_delete_recover_click"
      >
        <!-- 删除恢复按钮 -->
        <path
          d="M9.302 1.256a1.5 1.5 0 0 0-2.604 0l-1.704 2.98a.5.5 0 0 0 .869.497l1.703-2.981a.5.5 0 0 1 .868 0l2.54 4.444-1.256-.337a.5.5 0 1 0-.26.966l2.415.647a.5.5 0 0 0 .613-.353l.647-2.415a.5.5 0 1 0-.966-.259l-.333 1.242-2.532-4.431zM2.973 7.773l-1.255.337a.5.5 0 1 1-.26-.966l2.416-.647a.5.5 0 0 1 .612.353l.647 2.415a.5.5 0 0 1-.966.259l-.333-1.242-2.545 4.454a.5.5 0 0 0 .434.748H5a.5.5 0 0 1 0 1H1.723A1.5 1.5 0 0 1 .421 12.24l2.552-4.467zm10.89 1.463a.5.5 0 1 0-.868.496l1.716 3.004a.5.5 0 0 1-.434.748h-5.57l.647-.646a.5.5 0 1 0-.708-.707l-1.5 1.5a.498.498 0 0 0 0 .707l1.5 1.5a.5.5 0 1 0 .708-.707l-.647-.647h5.57a1.5 1.5 0 0 0 1.302-2.244l-1.716-3.004z"
        />
      </svg>
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="24"
        height="24"
        fill="currentColor"
        class="svg-success bi bi-chat-square-dots-fill"
        viewBox="0 0 16 16"
        v-if="post_data.is_deleted == 0"
        @click="quote_click"
      >
        <!-- 回复按钮 -->
        <path
          d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2.5a1 1 0 0 0-.8.4l-1.9 2.533a1 1 0 0 1-1.6 0L5.3 12.4a1 1 0 0 0-.8-.4H2a2 2 0 0 1-2-2V2zm5 4a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"
        />
      </svg>
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="24"
        height="24"
        fill="currentColor"
        class="svg-success bi bi-gift-fill"
        viewBox="0 0 16 16"
        v-if="!post_data.is_your_post && post_data.is_deleted == 0"
        @click="reward_click"
      >
        <!-- 打赏按钮 -->
        <path
          d="M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A2.968 2.968 0 0 1 3 2.506V2.5zm1.068.5H7v-.5a1.5 1.5 0 1 0-3 0c0 .085.002.274.045.43a.522.522 0 0 0 .023.07zM9 3h2.932a.56.56 0 0 0 .023-.07c.043-.156.045-.345.045-.43a1.5 1.5 0 0 0-3 0V3zm6 4v7.5a1.5 1.5 0 0 1-1.5 1.5H9V7h6zM2.5 16A1.5 1.5 0 0 1 1 14.5V7h6v9H2.5z"
        />
      </svg>
    </div>
    <div>
      <b-img
        :src="random_head_add"
        :class="'head_' + post_data.random_head"
      ></b-img>
    </div>
    <div class="post_content mb-2" style="margin-top: 2rem" ref="post_centent">
      <span
        v-html="post_data.content.replace(/\n/g, '<br>')"
        v-show="post_content_show"
        style="word-wrap: break-word; white-space: normal"
      ></span>
      <span v-show="!post_content_show" @click="post_content_show_click"
        >*此回帖已屏蔽*</span
      >
    </div>
    <div class="post_footer" ref="post_author_info">
      <span class="post_footer_text" @click="quote_click"
        >№{{ post_data.floor }} ☆☆☆</span
      >
      <span
        class="post_nick_name"
        :style="{ color: author_color[post_data.created_by_admin] }"
      >
        {{ post_data.nickname }}
      </span>
      <span class="post_footer_text">于</span>
      <span class="post_created_at">{{ post_data.created_at }}</span>
      <span class="post_footer_text"> 留言 ☆☆☆</span>
      <span v-if="thread_anti_jingfen" class="post_anti_jingfen">
        →{{ post_data.created_binggan_hash.slice(0, 5) }}
      </span>
    </div>

    <b-modal ref="reward_modal" id="reward_modal">
      <template v-slot:modal-header>
        <h5>打赏olo 给№{{ post_data.floor }}楼</h5>
      </template>
      <template v-slot:default>
        <p>
          友情提示：您在打赏回贴作者以后，将会扣除7%的手续费。
          <br />
          对方将获得：
          <span style="color: red"
            >{{ Math.floor(coin_reward_input * 0.93) }} </span
          >块奥利奥。
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
</template>


<script>
export default {
  components: {},
  props: {
    post_data: Object,
    thread_anti_jingfen: Number,
    random_head_add: String,
    admin_button_show: Boolean,
  },
  data: function () {
    return {
      name: "post_item",
      content_reward_input: "",
      coin_reward_input: "",
      reward_handling: false,
      author_color: ["", "#DD0000", "#5fb878"],
      post_content_show: true,
    };
  },
  computed: {
    forum_id() {
      return this.$store.state.Forums.CurrentForumData.id;
    },
  },
  created() {
    if (this.$store.state.User.UsePingbici) {
      const title_pingbici = JSON.parse(this.$store.state.User.ContentPingbici);
      for (var i = 0; i < title_pingbici.length; i++) {
        var reg = new RegExp(title_pingbici[i], "g");
        if (reg.test(this.post_data.content)) {
          this.post_content_show = false;
        }
      }
    }
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
          forum_id: this.$store.state.Forums.CurrentForumData.id,
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
    post_delete_click() {
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
    post_delete_recover_click() {
      var confirmed = confirm("要恢复这个已删除的回复吗？（消费300奥利奥）");
      if (confirmed == true) {
        const config = {
          method: "PUT",
          url: "/api/posts/recover/" + this.post_data.id,
          data: {
            binggan: this.$store.state.User.Binggan,
            thread_id: this.post_data.thread_id,
          },
        };
        axios(config)
          .then((response) => {
            if (response.data.code == 200) {
              alert("帖子恢复成功");
              this.$emit("get_posts_data");
            } else {
              alert(response.data.message);
            }
          })
          .catch((error) => alert(error));
      }
    },
    post_delete_click_admin() {
      var content = prompt("要用管理员权限删除这个回复吗？请输入理由");
      if (content != null) {
        const config = {
          method: "delete",
          url: "/api/admin/post_delete/" + this.post_data.id,
          data: {
            thread_id: this.post_data.thread_id,
            content: content,
          },
        };
        axios(config)
          .then((response) => {
            if (response.data.code == 200) {
              alert(response.data.message);
              this.$emit("get_posts_data");
            } else {
              alert(response.data.message);
            }
          })
          .catch((error) => alert(error));
      }
    },
    post_delete_recover_click_admin() {
      var content = prompt("要用管理员权限恢复这个回复吗？请输入理由");
      if (content != null) {
        const config = {
          method: "put",
          url: "/api/admin/post_recover/" + this.post_data.id,
          data: {
            thread_id: this.post_data.thread_id,
            content: content,
          },
        };
        axios(config)
          .then((response) => {
            if (response.data.code == 200) {
              alert(response.data.message);
              this.$emit("get_posts_data");
            } else {
              alert(response.data.message);
            }
          })
          .catch((error) => alert(error));
      }
    },
    post_delete_all_click_admin() {
      var content = prompt("要用管理员权限删除该饼干全部回复吗？请输入理由");
      if (content != null) {
        const config = {
          method: "post",
          url: "/api/admin/post_delete_all/",
          data: {
            post_id: this.post_data.id,
            thread_id: this.post_data.thread_id,
            content: content,
          },
        };
        axios(config)
          .then((response) => {
            if (response.data.code == 200) {
              alert(response.data.message);
              this.$emit("get_posts_data");
            } else {
              alert(response.data.message);
            }
          })
          .catch((error) => alert(error));
      }
    },
    ban_cookie_click_admin() {
      var content = prompt("你要永久粉碎这个饼干吗？请输入理由");
      if (content != null) {
        const config = {
          method: "post",
          url: "/api/admin/user_ban/",
          data: {
            post_id: this.post_data.id,
            thread_id: this.post_data.thread_id,
            content: content,
          },
        };
        axios(config)
          .then((response) => {
            if (response.data.code == 200) {
              alert(response.data.message);
              this.$emit("get_posts_data");
            } else {
              alert(response.data.message);
            }
          })
          .catch((error) => alert(error));
      }
    },
    lock_cookie_click_admin() {
      var content = prompt("你要封禁这个饼干吗？（默认3天）请输入理由");
      if (content != null) {
        const config = {
          method: "post",
          url: "/api/admin/user_lock/",
          data: {
            post_id: this.post_data.id,
            thread_id: this.post_data.thread_id,
            content: content,
          },
        };
        axios(config)
          .then((response) => {
            if (response.data.code == 200) {
              alert(response.data.message);
              this.$emit("get_posts_data");
            } else {
              alert(response.data.message);
            }
          })
          .catch((error) => alert(error));
      }
    },
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
    post_content_show_click() {
      this.post_content_show = true;
    },
  },
};
</script>

<style lang="scss">
.post_footer_text {
  color: #5fb878;
}
.post_footer {
  font-size: calc(0.8rem + 0.2vw);
}
.post_item {
  border-bottom: 1px solid #5fb878;
}
.emoji_img {
  max-height: 67px;
  max-width: 67px;
}
.quote_content {
  color: #777777;
}
.svg-success {
  color: #5fb878;
  cursor: pointer;
}
.svg-light {
  color: #666;
  cursor: pointer;
}
.post_content img {
  max-width: 100%;
}
</style>
