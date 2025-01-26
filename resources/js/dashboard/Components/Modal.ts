import useModal from '@/dashboard/composables/useModal';
import { defineComponent } from 'vue';

const Modal = defineComponent({
    setup() {
        const { vnode } = useModal();

        return () => vnode.value;
    },
});

export default Modal;
