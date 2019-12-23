import {AfterContentInit, Directive, ElementRef, Input} from '@angular/core';

import { PopupMenuService } from '../services/popup-menu.service';

@Directive({
  selector: '[appPopupMenuId]'
})
export class PopupMenuIdDirective implements AfterContentInit {
  @Input('appPopupMenuId') menuId: string;

  constructor(private readonly element: ElementRef,
              private readonly popup: PopupMenuService) {}

  ngAfterContentInit(): void {
    const menu = this.popup.getById(this.menuId);
    if (menu) {
      const wrapper = document.createElement('div');
      wrapper.classList.add('popup-menu-wrapper');
      this.element.nativeElement.parentNode.insertBefore(wrapper, this.element.nativeElement);
      wrapper.appendChild(this.element.nativeElement);
      console.log('menu', menu);
    } else {
      console.log('menu not found');
    }
  }

}
