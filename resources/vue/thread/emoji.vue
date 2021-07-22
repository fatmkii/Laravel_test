<template>
  <b-tabs class="emoji_tabs my-2" content-class="py-2 px2 " no-fade pills>
    <b-tab
      id="placeholder"
      title-link-class="placeholder"
      active
      title="多余占位"
    ></b-tab>
    <b-tab
      class="emoji_container"
      :title="emoji_data.name"
      v-for="emoji_data in emojis_data"
      :key="emoji_data.index"
      @click="emoji_open()"
      ><div v-show="emoji_show">
        <div
          class="emoji_box m-1 d-inline-flex"
          v-for="emoji_src in JSON.parse(emoji_data.emojis)"
          :key="emoji_src.index"
        >
          <b-img
            :src="emoji_src"
            fluid
            alt="Fluid-grow image"
            @click="emoji_click(emoji_src)"
          ></b-img>
        </div>
      </div>
    </b-tab>
  </b-tabs>
</template>


<script>
export default {
  components: {},
  props: {},
  data: function () {
    return {
      name: "emoji",
      emojis_data: "",
      emoji_show: false,
    };
  },
  methods: {
    emoji_click(emoji_src) {
      this.$emit("emoji_append", emoji_src);
    },
    emoji_open() {
      this.emoji_show = true;
      document
        .querySelector("#content_input")
        .scrollIntoView({ behavior: "smooth" });
    },
  },
  created() {
    const config = {
      method: "get",
      url: "/api/emoji",
    };
    axios(config)
      .then((response) => {
        if (response.data.code == 200) {
          this.emojis_data = response.data.data;
        }
      })
      .catch((error) => {
        console.log(error);
      }); // Todo:写异常返回代码
  },
};
</script>

<style lang="scss">
.nav {
  background-color: #eefaee;
}
.nav-link.active {
  background-color: #28a745 !important;
}

.emoji_box {
  max-width: 60px;
  max-height: 60px;
}
.emoji_container {
  max-height: 250px;
  overflow-y: auto;
}
#placeholder___BV_tab_button__ {
  display: none !important;
}
</style>