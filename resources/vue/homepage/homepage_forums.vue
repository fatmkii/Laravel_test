<template>
  <div>
    <b-card no-body class="mt-4">
      <template v-slot:header>
        <h4 class="mb-0">版面列表</h4>
      </template>

      <b-list-group
        class="list-group-item-action"
        v-for="forum in forums_data"
        :key="forum.id"
        @click="forum_view(forum.id)"
      >
        <b-card-body>
          <b-card-title>
            {{ forum.name }}
            <b-badge variant="secondary" pill class="float-right">{{
              forum.id
            }}</b-badge>
          </b-card-title>
          <b-card-text>{{ forum.description }} </b-card-text>
        </b-card-body>
      </b-list-group>
    </b-card>
  </div>
</template>


<script>
export default {
  components: {},
  props: {},
  data: function () {
    return {
      name: "homepage_forums",
      forums_data: "",
    };
  },
  methods: {
    forum_view: function (forum_id) {
      this.$router.push({ name: "forum", params: { id: forum_id } });
    },
  },
  mounted: function () {
    const config = {
      method: "get",
      url: "/api/forums/",
      data: {},
    };
    axios(config).then((response) => {
      if (response.data.code == 200) {
        this.forums_data = response.data.data;
      } else {
        console.log("获取版面内容失败"); //Todo:待完善失败处理
      }
    });
  },
};
</script>

<style lang="sass" scoped>
.card-body
  cursor: pointer
</style>>

