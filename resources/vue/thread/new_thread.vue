
<template>
  <div>
    <div class="row align-items-center mt-3">
      <div class="col-auto h5">
        <b-badge variant="secondary" pill class="float-left">
          {{ forum_id }}
        </b-badge>
        <span id="forum_name" @click="back_to_forum">{{ forum_name }}</span>
      </div>
      <div class="col-auto h5">
        <span>发表新话题</span>
      </div>
    </div>
    <div class="h6 my-2">昵称</div>
    <b-form-input id="nickname_input" v-model="nickname_input"></b-form-input>
    <div class="h6 my-2">标题</div>
    <b-form-input
      id="title_input"
      placeholder="标题，必填"
      v-model="title_input"
    ></b-form-input>
    <Emoji @emoji_append="emoji_append"></Emoji>
    <div class="h6 my-2">内容</div>
    <b-form-textarea
      id="content_input"
      ref="content_input"
      v-model="content_input"
      rows="3"
      max-rows="20"
    ></b-form-textarea>
    <div class="row align-items-center mt-3">
      <div class="col-6">
        <b-button
          variant="success"
          :disabled="new_thread_handling"
          @click="new_thread_handle"
          >发表</b-button
        >
        <p v-show="new_thread_handling">数据提交中……</p>
      </div>
      <div class="col-6"></div>
    </div>
    <hr />
    <div class="row align-items-center mt-3">
      <div class="col-4"><span class="h6 my-2">副标题</span></div>
      <div class="col-4"><span class="h6 my-2">锁帖时间</span></div>
      <div class="col-4"><span class="h6 my-2"></span></div>
    </div>
    <div class="row align-items-center mt-3">
      <div class="col-4">
        <b-form-select
          v-model="subtitles_selected"
          :options="subtitles_options"
        ></b-form-select>
      </div>
      <div class="col-4">
        <b-form-select
          v-model="nissin_time_selected"
          :options="nissin_time_options"
        ></b-form-select>
      </div>
      <div class="col-4"></div>
    </div>
    <hr />
    <div class="row align-items-center mt-3">
      <div class="col-4">
        <span class="h6 my-2">给标题换个颜色吗？（收费500奥利奥）</span>
      </div>
      <div class="col-4"><span class="h6 my-2"></span></div>
      <div class="col-4"><span class="h6 my-2"></span></div>
    </div>
    <div class="row align-items-center mt-3">
      <div class="col-4">
        <b-form-input
          placeholder="#212529"
          v-model="title_color_input"
        ></b-form-input>
      </div>
      <div class="col-4"></div>
      <div class="col-4"></div>
    </div>
  </div>
</template>


<script>
import Emoji from "./emoji.vue";

export default {
  components: { Emoji },
  props: {
    forum_id: Number, //来自router
  },
  data: function () {
    return {
      name: "new_thread",
      new_thread_handling: false,
      nickname_input: "= =",
      content_input: "",
      title_input: "",
      subtitles_options: [],
      subtitles_selected: "[闲聊]",
      nissin_time_options: [
        { value: 86400, text: "24小时" },
        { value: 172800, text: "48小时" },
        { value: 259200, text: "72小时" },
      ],
      nissin_time_selected: 86400,
      title_color_input: "",
    };
  },
  computed: {
    forum_name() {
      var forum_data = this.$store.getters.ForumData(this.forum_id);
      if (forum_data) {
        return forum_data.name;
      }
    },
  },

  methods: {
    back_to_forum() {
      this.$router.push({ name: "forum", params: { forum_id: this.forum_id } });
    },
    new_thread_handle() {
      this.new_thread_handling = true;
      const config = {
        method: "post",
        url: "/api/threads/create",
        data: {
          binggan: this.$store.state.User.Binggan,
          forum_id: this.forum_id,
          title: this.title_input,
          content: this.content_input,
          nickname: this.nickname_input,
          subtitle: this.subtitles_selected,
          nissin_time: this.nissin_time_selected,
          title_color: this.title_color_input,
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
            setTimeout(() => {
              this.back_to_forum();
            }, 1500);
          } else {
            this.new_thread_handling = false;
            alert(response.data.message);
          }
        })
        .catch((error) => {
          this.new_thread_handling = false;
          alert(error);
        }); // Todo:写异常返回代码
    },
    emoji_append(emoji_src) {
      this.content_input += "<img src='" + emoji_src + "' class='emoji_img'>";
      this.$refs.content_input.focus();
    },
  },
  created() {
    const config = {
      method: "get",
      url: "/api/subtitles",
      data: {},
    };
    axios(config)
      .then((response) => {
        this.subtitles_options = response.data.data;
        // 如果不是管理员，就删除部分管理员专用选项
        if (!this.$store.state.User.AdminStatus) {
          this.subtitles_options.forEach((subtitles_option, index) => {
            if (subtitles_option.for_admin == 1) {
              this.subtitles_options.splice(index, 1);
            }
          });
        }
      })
      .catch((error) => {
        alert(error);
      }); // Todo:写异常返回代码;
  },
};
</script>

<style lang="scss" scoped>
#forum_name {
  cursor: pointer;
}
</style>