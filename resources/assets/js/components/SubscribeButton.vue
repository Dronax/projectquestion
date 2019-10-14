<template>
  <button :class="classes" @click="subscribe">Subscribe</button>
</template>

<script>
export default {
  props: ['active'],

  data() {
    return {
      activeHov: this.active
    }
  },

  methods: {
    subscribe() {
      axios[(this.activeHov ? 'delete' : 'post')](location.pathname + '/subscriptions');

      this.activeHov = ! this.activeHov;

      flash('Subscribed!');
    }
  },

  computed: {
    classes() {
      return ['btn', this.activeHov ? 'btn-primary' : 'btn-success'];
    }
  }
}
</script>

