<template>
  <div>
    <div>
      <b-table-simple
        style="width: 100%;"
        class="table">
        <b-thead>
          <b-tr>
            <b-th class="text-center" style="width: 35%;">Nama</b-th>
            <b-th style="width: 25%;">Departemen</b-th>
            <b-th class="text-center" colspan="2">Status</b-th>
            <b-th class="text-center">Opsi</b-th>
          </b-tr>
        </b-thead>
        <b-tbody v-for="(participant, i) in data" :key="i">
          <b-tr>
            <b-td>
              <b-media vertical-align="center">
                <template #aside>
                  <b-avatar
                    variant="light-primary"
                    :src="participant.datauser.avatar ? participant.datauser.avatarlink : null"
                    :text="avatarText(participant.datauser.name)"
                    size="36"/>
                </template>
                <span class="font-weight-bold d-block text-nowrap">
                  <strong> {{ participant.datauser.name }} </strong>
                </span>
                <small> {{ participant.datauser.nik }} </small> 
                <small> | {{ participant.datauser.position ? participant.datauser.position.name : ''}} </small> 
              </b-media>
            </b-td>
            <b-td>
              <b-badge
                style="background-color: CornflowerBlue;"
                >
                {{ participant.datauser.position ? participant.datauser.position.deptname.name : '-' }}
              </b-badge>
            </b-td>
            <b-td class="text-center">
              <b-badge
                :variant="`light-${badgeIsDone[2][participant.isdone]}`"
                class="badge-glow">
                {{ badgeIsDone[1][participant.isdone] }}
              </b-badge>
            </b-td> 
            <b-td class="text-center">
              <div v-if="participant.isdone !== 3">
                <b-badge
                  pill
                  :variant="badgeIsPassed[2][participant.ispassed]"
                  class="badge-glow">
                  {{ badgeIsPassed[1][participant.ispassed] }}
                </b-badge>
              </div>
              <div v-else>
                <b-icon
                  variant="success" 
                  icon="circle-fill" 
                  animation="throb" 
                  font-scale="0.5">
                </b-icon>
              </div>
            </b-td>
            <b-td class="text-center">
              <b-button-group>
                <b-button
                  @click="$emit('infodata', participant)"
                  class="btn-icon"
                  size="sm"
                  type="button"
                  variant="flat-primary">
                <feather-icon icon="InfoIcon" size="18"/>
                </b-button>
                <b-button
                  v-if="participant.isdone === 2 && $can('update', 'AppOKMSchedule')"
                  @click="$emit('deletedata', participant.id)"
                  class="btn-icon"
                  size="sm"
                  type="button"
                  variant="flat-danger">
                <feather-icon icon="XIcon" size="18"/>
                </b-button>
              </b-button-group>
            </b-td>
          </b-tr>
        </b-tbody>
      </b-table-simple>
    </div>
  </div>
</template>

<script>
import {
  BTableSimple, BThead, BTbody, BTh, BTd, BTr,
  BImg,
  BButton, BButtonGroup,
  BMedia, BAvatar,
  BBadge, BIcon,
} from 'bootstrap-vue'
import { avatarText } from '@core/utils/filter'

export default {
  components: {
    BTableSimple, BThead, BTbody, BTh, BTd, BTr,
    BImg,
    BButton, BButtonGroup,
    BMedia, BAvatar,
    BBadge, BIcon,
  },
  props: {
    data: {
      type: Array
    },
  },
  data(){
    return{
      avatarText,
      badgeIsDone: [
        { 1: 1, 2: 2, 3: 3 },
        { 1: 'Selesai', 2: 'Belum Dikerjakan', 3: 'Proses' },
        { 1: 'primary', 2: 'secondary', 3: 'success' },
      ],
      badgeIsPassed: [
        { 1: 1, 2: 2 },
        { 1: 'Lulus', 2: 'Tidak Lulus' },
        { 1: 'success', 2: 'danger' },
      ],
    }
  },
  methods: {
    //
  },
  mounted(){
    // this.setcount()
  }
}
</script>

<style lang="scss" scoped>

</style>