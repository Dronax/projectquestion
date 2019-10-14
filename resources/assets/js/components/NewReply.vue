<template>
<div>
    <div v-if="signedIn">
      <div class="form-group">
        <textarea name="body" id="body" class="form-control" placeholder="Type a answer" rows="5" required v-model="body"></textarea>
      </div>

      <button type="submit" class="btn btn-success" @click="addReply">Publish</button>
    </div>

    <p class="text-center" v-else>
        Please <a href="/login">login</a> for reply!
    </p>
</div>
</template>

<script>
export default {
  data() {
    return {
      body: ''
    }
  },

  computed: {
    signedIn() {
      return window.App.signIn;
    }
  },

  methods: {
    addReply() {
      axios.post(location.pathname + '/replies', { body: this.body })
          .then(response => {
            this.body = '';

            flash('Ваш ответ был добалвен!');

            this.$emit('created', response.data);
          });
    }
  }
}
</script>
