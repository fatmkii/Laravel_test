<template>
  <div>
    <b-card title="开发中" header-tag="header" class="mt-4">
      <template v-slot:header>
        <h4 class="mb-0">公告栏</h4>
      </template>
      <b-card-text>{{ bulletin_text }}</b-card-text>
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
      bulletin_text: "不知道要开发多久……",
    };
  },
  methods: {},
};
</script>