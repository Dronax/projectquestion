<template>
  <button type="submit" :class="className" @click="toggle">
    <span v-text="count"></span> <span> Liked</span>
  </button>
</template>


<script>
export default {
  props: ['reply'],

  data() {
    return {
      count: this.reply.favoritesCount,
      active: this.reply.isFavorited,
    }
  },

  computed: {
    className() {
      return [
        'btn', 
        this.active ? 'btn-success' : 'btn-default'
      ];
    },

    endpoint() {
      return '/replies/' + this.reply.id + '/favorites'; 
    }
  },

  methods: {
    toggle() {
        this.active ? this.destroy() : this.create();
    },

    create() {
        axios.post(this.endpoint);

        this.active = true;
        this.count++;
    },

    destroy() {
        axios.delete(this.endpoint);

        this.active = false;
        this.count--;
    },
  }
}
</script>
