<template lang="html">
  <tr>
      <td :colspan="colspan" class="text-center text-grey-5 q-td--no-hover">
        <div class='text-h5 q-pa-lg q-mt-lg q-mb-lg'>
            <q-icon name='info_outline' size="lg" class="q-mr-md" />
            {{ message }}
        </div>
      </td>
  </tr>
</template>

<script>
export default {
    name: 'TableRowNoContent',

    props: {
        message: {
            type: String,
            default: "Hups, nothing found"
        },

        colspan: {
            type: [String, Number],
            default: 10,
        }
    }
}
</script>
