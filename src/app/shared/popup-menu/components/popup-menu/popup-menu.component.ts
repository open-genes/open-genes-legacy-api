import { Component, EventEmitter, Input, OnChanges, OnInit, Output, SimpleChanges } from '@angular/core';

import { PopupMenuService } from '../../services/popup-menu.service';
import { PopupMenuItemComponent } from '../popup-menu-item/popup-menu-item.component';

@Component({
  selector: 'app-popup-menu',
  template: '',
  styleUrls: ['./popup-menu.component.scss']
})
export class PopupMenuComponent implements OnInit, OnChanges {
  @Input() id: string;
  @Output() opened: EventEmitter<void>;
  @Output() closed: EventEmitter<void>;
  public items: PopupMenuItemComponent[];

  constructor(private readonly popupMenuService: PopupMenuService) {
    this.opened = new EventEmitter();
    this.closed = new EventEmitter();
    this.items = [];
  }

  ngOnInit() {
  }

  ngOnChanges(changes: SimpleChanges): void {
    if (changes.id && changes.id.currentValue && changes.id.firstChange === true) {
      this.popupMenuService.register(this);
    }
  }

}
