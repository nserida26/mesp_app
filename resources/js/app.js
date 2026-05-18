import './bootstrap';
import { createApp } from 'vue';
import PublicVerification from './components/PublicVerification.vue';
import AdminDashboard from './components/AdminDashboard.vue';

const components = {
    PublicVerification,
    AdminDashboard,
};

document.querySelectorAll('[data-vue-component]').forEach((element) => {
    const componentName = element.dataset.vueComponent;
    const component = components[componentName];

    if (!component) {
        return;
    }

    const props = element.dataset.props ? JSON.parse(element.dataset.props) : {};
    createApp(component, props).mount(element);
});
