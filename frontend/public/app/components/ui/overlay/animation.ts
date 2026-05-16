import { gsap } from 'gsap'

export const enterTopAnimation = (el: Element, done: () => void) => {
  gsap.fromTo(
    el,
    { opacity: 0, y: -10 },
    {
      opacity: 1,
      y: 0,
      duration: 0.2,
      ease: 'none',
      onComplete: done
    }
  )
}

export const leaveTopAnimation = (el: Element, done: () => void) => {
  gsap.to(el, {
    opacity: 0,
    y: -10,
    duration: 0.2,
    ease: 'none',
    onComplete: done
  })
}

export const enterBottomAnimation = (el: Element, done: () => void) => {
  gsap.fromTo(
    el,
    { opacity: 0, y: 10 },
    {
      opacity: 1,
      y: 0,
      duration: 0.2,
      ease: 'none',
      onComplete: done
    }
  )
}

export const leaveBottomAnimation = (el: Element, done: () => void) => {
  gsap.to(el, {
    opacity: 0,
    y: 10,
    duration: 0.2,
    ease: 'none',
    onComplete: done
  })
}

export const enterLeftAnimation = (el: Element, done: () => void) => {
  gsap.fromTo(
    el,
    { opacity: 0, x: -10 },
    {
      opacity: 1,
      x: 0,
      duration: 0.2,
      ease: 'none',
      onComplete: done
    }
  )
}

export const leaveLeftAnimation = (el: Element, done: () => void) => {
  gsap.to(el, {
    opacity: 0,
    x: -10,
    duration: 0.2,
    ease: 'none',
    onComplete: done
  })
}

export const enterRightAnimation = (el: Element, done: () => void) => {
  gsap.fromTo(
    el,
    { opacity: 0, x: 10 },
    {
      opacity: 1,
      x: 0,
      duration: 0.2,
      ease: 'none',
      onComplete: done
    }
  )
}

export const leaveRightAnimation = (el: Element, done: () => void) => {
  gsap.to(el, {
    opacity: 0,
    x: 10,
    duration: 0.2,
    ease: 'none',
    onComplete: done
  })
}
