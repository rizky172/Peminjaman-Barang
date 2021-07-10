<template>
    <div>
        <b-badge :variant="getClass">{{ status }}</b-badge>
    </div>
</template>
<script>
export default {
    name:'Badges',
    props: {
            status: String,
            warning: {
                type: Array,
                default: function () {
                    return ['pending'];
                }
            },
            success: {
                type: Array,
                default: function () {
                    return ['approved'];
                }
            },
            danger: {
                type: Array,
                default: function () {
                    return ['rejected'];
                }
            },
            primary: {
                type: Array,
                default: function () {
                    return ['return'];
                }
            }
    },
    computed: {
         getClass: function () {
            if (this.warning.includes(this.status)){
                return 'warning';
            }
            if (this.success.includes(this.status)){
                return 'success';
            }
            if (this.danger.includes(this.status)){
                return 'danger';
            }
            if (this.primary.includes(this.status)){
                return 'primary';
            }
        }
    }

}
</script>
<style></style>