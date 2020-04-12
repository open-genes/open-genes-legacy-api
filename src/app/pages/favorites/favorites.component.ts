import { Component, OnInit } from '@angular/core';
import {TranslateService} from '@ngx-translate/core';
import {FavoritesService} from './favorites.service';
import {Genes} from 'src/app/core/models';
import {ApiService} from 'src/app/core/services/api.service';

@Component({
  selector: 'app-favorites',
  templateUrl: './favorites.component.html'
})
export class FavoritesComponent implements OnInit {
  genes: Genes[];
  error: number;

    constructor(
      private readonly apiService: ApiService,
      public translate: TranslateService,
      private favsService: FavoritesService
  ) { }

  favsItems = this.favsService.items;

  ngOnInit() {
    this.getGenes();
  }

  private getFavsList() {
    this.favsService.getItems();
  }

  private getGenes() {
    this.apiService.getGenes().subscribe((genes) => {
      this.genes = genes;
    }, error => this.error = error);
  }
}
