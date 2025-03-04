export default defineNuxtPlugin(() => {
    addRouteMiddleware('admin', () => {
      const authStore = useAuthStore();
      
      if (!authStore.isAdmin) {
        return navigateTo('/admin/login');
      }
    });
  });