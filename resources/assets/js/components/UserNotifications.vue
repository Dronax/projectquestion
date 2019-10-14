<template>
  <li class="nav-item dropdown" v-show="notifications.length">
    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Notifications
    </a>

    <div v-for="notification in notifications" :key="notification.id" class="dropdown-menu" aria-labelledby="navbarDropdown">
      <a :href="notification.data.link" class="dropdown-item" v-text="notification.data.message" @click="markAsRead(notification)"></a>
    </div>
  </li>
</template>

<script>
export default {
  data() {
    return {
      notifications: [],

    }
  },

  created() {
    axios.get('/profiles/'+ window.App.user.name + '/notifications')
      .then(response => this.notifications = response.data)
  },

  methods: {
      markAsRead(notification) {
        axios.delete('/profiles/'+ window.App.user.name + '/notifications/' + notification.id);
      }
  }
}
</script>
