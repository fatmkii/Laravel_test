<template>
  <div>
    <b-modal ref="login-modal" id="login-modal">
      <template v-slot:modal-header>
        <h6>导入饼干</h6>
      </template>

      <template v-slot:default>
        <b-input-group class="mt-3">
          <b-form-input
            placeholder="请输入饼干"
            v-model="binggan_input"
          ></b-form-input>
          <b-button
            variant="outline-success"
            type="submit"
            @click="login_handle"
          >
            导入</b-button
          >
        </b-input-group>
        <b-button variant="success" class="mt-4" @click="login_handle2"
          >不用csrf登录</b-button
        >
        <b-button variant="success" class="mt-4" @click="get_user"
          >获得用户名</b-button
        >
        <p>用户名：{{ user }}</p>
        <b-button variant="success" class="mt-4" @click="get_forums"
          >获取数据</b-button
        >
      </template>
      w
      <template v-slot:modal-footer="{ cancel }">
        <b-button size="sm" variant="outline-seco ndary" @click="cancel()">
          取消
        </b-button>
      </template>
    </b-modal>
  </div>
</template>


<script>
export default {
  components: {},
  props: {},
  data: function () {
    return {
      name: "login_modal",
      binggan_input: "",
      user: "",
    };
  },
  mounted: function () {
    // this.$bvModal.show('login-modal');
    this.$refs["login-modal"].show();
  },
  methods: {
    login_handle() {
      axios
        .get("/sanctum/csrf-cookie")
        .then((response) => {
          const config = {
            method: "post",
            url: "/api/login",

            data: {
              binggan: this.binggan_input,
            },
          };
          axios(config).then((response) => {
            console.log(response);
          });
        })
        .catch((error) => console.log(error));
    },
    login_handle2() {
      const config = {
        method: "post",
        url: "/api/login",
        data: {
          binggan: this.binggan_input,
        },
      };
      axios(config)
        .then((response) => {
          console.log(response);
        })
        .catch((error) => console.log(error));
    },
    get_user() {
      const config = {
        method: "get",
        url: "/api/get_user",
        data: {},
      };
      axios(config)
        .then((response) => {
          console.log(response);
        })
        .catch((error) => console.log(error));
    },
    get_forums() {
      const config = {
        method: "post",
        url: "/api/forums/",
        data: {},
        headers: { withCredentials: true },
      };
      axios(config).then((response) => {
        if (response.data.code == 200) {
          this.$store.commit("ForumsData_set", response.data.data);
        } else {
          console.log("获取版面内容失败"); //Todo:待完善失败处理
        }
      });
    },
  },
};
</script>