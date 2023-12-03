<template>
  <div class="lb-pull-refresh" ref="root">
    <div
      class="lb-pull-refresh__track"
      :style="trackStyle"
      @touchstart="onTouchStart"
      @touchmove="onTouchMove"
      @touchend="onTouchEnd"
      @touchcancel="onTouchend"
    >
      <div :style="getHeadStyle" class="lb-pull-refresh__head text-muted">
        <div v-if="status === 'pulling'" class="pull-text">
          <i class="ki-duotone ki-double-down fs-2x"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
        </div>
        <div v-else-if="status === 'loosing'" class="pull-text">
          <i class="ki-duotone ki-double-up fs-2x"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
        </div>
        <div v-else-if="status === 'loading'" class="pull-text-loading">
          <span class="spinner-border w-20px h-20px align-middle"></span>
        </div>
      </div>
      <slot></slot>
    </div>
  </div>
</template>

<script>
import { ref, watch, reactive, nextTick, computed, defineComponent, toRefs } from 'vue';

import useScrollParent from '@/composables/useScrollParent';
import useTouch from '@/composables/useTouch';

const DEFAULT_HEAD_HEIGHT = 50;

function getScrollTop(el) {
  const top = 'scrollTop' in el ? el.scrollTop : el.pageYOffset
  return Math.max(top, 0);
}

function preventDefault(event, isStopPropagation) {
  if (typeof event.cancelable !== 'boolean' || event.cancelable) {
    event.preventDefault()
  }
  if (isStopPropagation) {
    stopPropagation(event)
  }
}

export default defineComponent({
  name: 'pull-refresh',
  props: {
    disabled: Boolean,
    successText: {
      type: String,
      default: '刷新成功'
    },
    pullingText: {
      type: String,
      default: '下拉即可刷新...'
    },
    loosingText: {
      type: String,
      default: '释放即可刷新...'
    },
    loadingText: {
      type: String,
      default: '加载中...'
    },
    pullDistance: [Number, String],
    modelValue: {
      type: Boolean,
      default: false,
    },
    successDuration: {
      type: [Number, String],
      default: 500,
    },
    animationDuration: {
      type: [Number, String],
      default: 300,
    },
    headHeight: {
      type: [Number, String],
      default: DEFAULT_HEAD_HEIGHT,
    },
  },

  emits: ['refresh', 'update:modelValue'],

  setup(props, { emit, slots }) {
    let reachTop = false;
    const TEXT_STATUS = ['pulling', 'loosing', 'success']

    const root = ref(null)
    let scrollParent = useScrollParent(root)

    const state = reactive({
      status: 'normal',
      distance: 0,
      duration: 0
    });
    
    const trackStyle = ref({});

    const touch = useTouch();

    const getHeadStyle = computed(() => {
      if (props.headHeight !== DEFAULT_HEAD_HEIGHT) {
        return {
          height: `${props.headHeight}px`
        }
      }
      return {}
    })

    const isTouchable = () =>
      state.status !== 'loading' &&
      state.status !== 'success' &&
      !props.disabled;

    const ease = (distance) => {
      const pullDistance = +(props.pullDistance || props.headHeight);

      if (distance > pullDistance) {
        if (distance < pullDistance * 2) {
          distance = pullDistance + (distance - pullDistance) / 2;
        } else {
          distance = pullDistance * 1.5 + (distance - pullDistance * 2) / 4;
        }
      }

      return Math.round(distance);
    };

    const setTrackStyle = () => {
      return {
        transitionDuration: `${state.duration}ms`,
        transform: state.distance
          ? `translate3d(0,${state.distance}px, 0)`
          : '',
      }
    }

    const setStatus = (distance, isLoading) => {
      const pullDistance = +(props.pullDistance || props.headHeight);
      state.distance = distance;

      if (isLoading) {
        state.status = 'loading';
      } else if (distance === 0) {
        state.status = 'normal';
      } else if (distance < pullDistance) {
        state.status = 'pulling';
      } else {
        state.status = 'loosing';
      }
      trackStyle.value = setTrackStyle()
    };

    const getStatusText = () => {
      const { status } = state;
      if (status === 'normal') {
        return '';
      }
      return props[`${status}Text`] || '';
    };

    const showSuccessTip = () => {
      state.status = 'success';

      setTimeout(() => {
        setStatus(0);
      }, +props.successDuration);
    };

    const checkPosition = (event) => {
      reachTop = getScrollTop(scrollParent.value) === 0;

      if (reachTop) {
        state.duration = 0;
        touch.start(event);
      }
    };

    const onTouchStart = (event) => {
      if (isTouchable()) {
        checkPosition(event);
      }
    };

    const onTouchMove = (event) => {
      if (isTouchable()) {
        if (!reachTop) {
          checkPosition(event);
        }

        const { deltaY } = touch;
        touch.move(event);

        if (reachTop && deltaY.value >= 0 && touch.isVertical()) {
          preventDefault(event);
          setStatus(ease(deltaY.value));
        }
      }
    };

    const onTouchEnd = () => {
      if (reachTop && touch.deltaY.value && isTouchable()) {
        state.duration = +props.animationDuration;

        if (state.status === 'loosing') {
          setStatus(+props.headHeight, true);
          emit('update:modelValue', true);

          // ensure value change can be watched
          nextTick(() => emit('refresh'));
        } else {
          setStatus(0);
        }
      }
    };

    watch(
      () => props.modelValue,
      (value) => {
        state.duration = +props.animationDuration;

        if (value) {
          setStatus(+props.headHeight, true);
        } else if (slots.success || props.successText) {
          showSuccessTip();
        } else {
          setStatus(0, false);
        }
      }
    )

    return {
      ...toRefs(state),
      root,
      trackStyle,
      TEXT_STATUS,
      getHeadStyle,
      getStatusText,
      onTouchStart,
      onTouchMove,
      onTouchEnd
    }
  }
})
</script>

<style>
:root {
  --lb-pull-refresh-head-height: 45px;
}

.lb-pull-refresh {
  user-select: none;
  height: 100%;
}
.lb-pull-refresh__track {
  position: relative;
  height: 100%;
  transition-property: transform;
}

.lb-pull-refresh__head {
    position: absolute;
    left: 0;
    width: 100%;
    height: var(--lb-pull-refresh-head-height);
    overflow: hidden;
    text-align: center;
    transform: translateY(-100%);
  }
</style>
