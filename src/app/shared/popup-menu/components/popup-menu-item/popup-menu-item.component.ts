import { Component, Host, OnInit, Optional } from '@angular/core';

import { PopupMenuComponent } from '../popup-menu/popup-menu.component';

@Component({
  selector: 'app-popup-menu-item',
  templateUrl: './popup-menu-item.component.html',
  styleUrls: ['./popup-menu-item.component.scss']
})
export class PopupMenuItemComponent implements OnInit {

  constructor(@Host() @Optional() private readonly menu: PopupMenuComponent) { }

  ngOnInit() {
    if (this.menu) {
      this.menu.items.push(this);
    }
  }

}
