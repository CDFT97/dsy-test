import { defineStore } from "pinia";
import Swal from "sweetalert2";

export const useAlertsStore = defineStore("alerts", {
    state: () => ({
        
    }),
    getters: {
      
    },
    actions: {
        defineToast() {
            const toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                  toast.addEventListener("mouseenter", Swal.stopTimer);
                  toast.addEventListener("mouseleave", Swal.resumeTimer);
                },
            });
            return toast
        },
        success(message) {
            const toast = this.defineToast();
            toast.fire({
                icon: "success",
                title: message,
            });
        },
        error(message) {
            const toast = this.defineToast();
            toast.fire({
                icon: "error",
                title: message,
            });
        },
        warning(message) {
            const toast = this.defineToast();
            toast.fire({
                icon: "warning",
                title: message,
            });
        }
    }
});