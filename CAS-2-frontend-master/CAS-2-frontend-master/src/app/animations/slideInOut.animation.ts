import {state, style, trigger} from '@angular/animations';

export const slideInOut = trigger('slideInOut', [
  // ...
  state('in', style({
    'max-height': '500px', opacity: '1', visibility: 'visible', display: 'block',
  })),
  state('out', style({
    'max-height': '0px', opacity: '0', visibility: 'hidden', display: 'none'
  }))/*,
  transition('in => out', [group([
    animate('50ms ease-in-out', style({
      'opacity': '0'
    })),
    animate('300ms ease-in-out', style({
      'max-height': '0px',
    })),
    animate('300ms ease-in-out', style({
      'visibility': 'hidden',

    })),
    ]
  )]),
  transition('out => in', [group([
      animate('1ms ease-in-out', style({
        'visibility': 'visible',

      })),
      animate('300ms ease-in-out', style({
        'max-height': '500px',
      })),
      animate('300ms ease-in-out', style({
        'opacity': '1',
      }))
    ]
  )])*/
]);
