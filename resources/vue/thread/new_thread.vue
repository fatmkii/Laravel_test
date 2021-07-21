
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
    <b-form-input id="title_input" v-model="title_input"></b-form-input>
    <div class="h6 my-2">内容</div>
    <b-form-textarea
      id="content_input"
      v-model="content_input"
      rows="3"
      max-rows="20"
    ></b-form-textarea>
    <div class="row align-items-center mt-3">
      <div class="col-6">
        <b-button variant="success" @click="new_thread_handle">发表</b-button>
      </div>
      <div class="col-6">放点别的？</div>
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
      nickname_input: "= =",
      content_input: "",
      title_input: "",
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
      const config = {
        method: "post",
        url: "/api/threads/create",
        data: {
          binggan: this.$store.state.User.Binggan,
          forum_id: this.forum_id,
          title: this.title_input,
          content: this.content_input,
          nickname: this.nickname_input,
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
          }
          setTimeout(() => {
            this.back_to_forum();
          }, 1500);
        })
        .catch((error) => console.log(error)); // Todo:写异常返回代码
    },
  },
  created() {},
};
</script>

<style lang="scss" scoped>
#forum_name {
  cursor: pointer;
}
</style>