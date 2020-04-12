import {AfterViewInit, Component, OnChanges, OnInit} from '@angular/core';

import {ApiService} from 'src/app/core/services/api.service';
import {Genes, Filter} from 'src/app/core/models';
import {TranslateService} from '@ngx-translate/core';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html'
})
export class HomeComponent implements OnInit {
  genes: Genes[];
  lastGenes: Genes[];
  error: number;

  constructor(
    private readonly apiService: ApiService,
    private readonly translate: TranslateService
  ) { }

  ngOnInit() {
    this.getGenes();
    this.getLastEditedGenes();
  }

  private getLastEditedGenes() {
    this.apiService.getLastEditedGene().subscribe((genes) => {
      this.lastGenes = genes;
    });
  }

  private getGenes() {
    this.apiService.getGenes().subscribe((genes) => {
      this.genes = genes;
    }, error => this.error = error);
  }
}
