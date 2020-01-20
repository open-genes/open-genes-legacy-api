import {ChangeDetectionStrategy, Component, ElementRef, HostBinding, TemplateRef} from '@angular/core';
import {SafeStyle} from '@angular/platform-browser';

@Component({
  selector: 'app-menu',
  template: '<ng-container *ngTemplateOutlet="menuTemplate"></ng-container>',
  styleUrls: ['./app-menu.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush,
})
export class AppMenuComponent {
  @HostBinding('attr.role')
  role = 'list';

  @HostBinding('class.__visible')
  isMenuVisible = false;

  @HostBinding('style')
  safeStyles: SafeStyle;

  menuTemplate: TemplateRef<any>;

  constructor(public elRef: ElementRef) {
  }
}
