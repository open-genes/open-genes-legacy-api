import {Component, EventEmitter, Input, OnChanges, OnDestroy, OnInit, Output} from '@angular/core';
import {FilterService} from 'src/app/core/services/filter.service';

import {Subject} from 'rxjs';

import {Filter, Genes} from 'src/app/core/models';
import {GenesListService} from './genes-list.service';
import {FavoritesService} from 'src/app/pages/favorites/favorites.service';
import {ActivatedRoute} from '@angular/router';

@Component({
  selector: 'app-genes-list',
  templateUrl: './genes-list.component.html',
  styleUrls: ['./genes-list.component.scss']
})
export class GenesListComponent implements OnInit, OnChanges, OnDestroy {

  @Input() dataSource: any;
  @Output() filterCluster = new EventEmitter<number[]>();
  @Output() filterExpression = new EventEmitter<string>();
  @Output() filtersCleared = new EventEmitter();
  searchedData: Genes[];
  genesPerPage = 30;
  loadedGenesQuantity = this.genesPerPage;
  isLoading = true;
  asCards = true;
  private subscription$ = new Subject();
  private filters: Filter;

  constructor(
    private readonly genesListService: GenesListService,
    private route: ActivatedRoute,
    private favsService: FavoritesService,
    private readonly filterService: FilterService
  ) {
    this.filters = {
      byName: false,
      byAge: false,
      byExpressionChange: null,
      byClasses: []
    };
    this.genesListService.register(this);
  }

  ngOnInit() {
  }

  ngOnChanges() {
    this.searchedData = this.dataSource;
    this.isLoading = false;
  }

  getSearchedData(e: Genes[]) {
    this.searchedData = e;
  }

  geneView() {
    this.asCards = !this.asCards;
  }

  public addToFavsList(geneId: number) {
    this.favsService.addToFavorites(geneId);
    window.alert('Your product has been added to the cart!');
  }

  getGenes(sortBy) {
    if (sortBy === 'name') {
      this.filters.byName ? this.reverse() : this.sortByName();
      this.filters.byName = !this.filters.byName;
    } else {
      this.filters.byAge ? this.reverse() : this.sortByAge();
      this.filters.byAge = !this.filters.byAge;
    }
  }

  private reverse() {
    this.searchedData.reverse();
  }

  private sortByName() {
    this.searchedData.sort((a, b) => {
      const A = (a.symbol + a.name).toLowerCase();
      const B = (b.symbol + b.name).toLowerCase();
      return A > B ? 1 : A < B ? -1 : 0;
    });
  }

  private sortByAge() {
    this.searchedData.sort((a, b) => {
      return a.origin.order - b.origin.order;
    });
  }

  public loadMoreGenes() {
    if (this.searchedData.length >= this.loadedGenesQuantity) {
      this.loadedGenesQuantity += this.genesPerPage;
    }
  }

  ngOnDestroy(): void {
    this.subscription$.unsubscribe();
  }

  filterByFuncClusters(id) {
    this.filterService.clustersFilter(id, this.filters);
    this.isLoading = true;
    this.filterCluster.emit(this.filters.byClasses);
  }

  filterByExpressionChange(expression: string) {
    if (this.filters.byExpressionChange !== expression) {
      this.filters.byExpressionChange = expression;
    } else {
      this.filters.byExpressionChange = null;
    }
    this.isLoading = true;
    this.filterExpression.emit(this.filters.byExpressionChange);
  }

  /**
   * Сброс фильтров
   */
  clearFilters(filter: string) {
    if (filter === 'all') {
      this.filters = {
        byName: false,
        byAge: false,
        byClasses: [],
        byExpressionChange: null
      };
      this.filtersCleared.emit();
    } else if (filter === 'name') {
      this.filters.byName = false;
      this.filtersCleared.emit();
    } else if (filter === 'age') {
      this.filters.byAge = false;
      this.filtersCleared.emit();
    } else if (filter === 'classes') {
      this.filters.byClasses = [];
      this.filtersCleared.emit();
    } else if (filter === 'expressionChange') {
      this.filters.byExpressionChange = null;
      this.filtersCleared.emit();
    }
  }
}
