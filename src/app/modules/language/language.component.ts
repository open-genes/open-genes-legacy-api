import {Component, Input} from '@angular/core';
import {TranslateService} from '@ngx-translate/core';
import {environment} from '../../../environments/environment';

@Component({
  selector: 'app-language',
  templateUrl: './language.component.html',
  styleUrls: ['./language.component.scss']
})
export class LanguageComponent {
  @Input()
  relative: string;

  constructor(public translate: TranslateService) {
  }

  changeLanguage(language) {
    const currentIndex = environment.languages.indexOf(this.translate.currentLang);
    const nextIndex = (currentIndex + 1) % environment.languages.length;
    const nextLang = environment.languages[nextIndex];

    function languageSelector(l) {
      if (l === 'ru') {
        return  environment.languages[0];
      } else if (l === 'en') {
        return environment.languages[1];
      } else {
        return nextLang;
      }
    }

    const languageSet = languageSelector(language);

    this.translate.use(languageSet);
    localStorage.setItem('lang', languageSet);
    window.location.reload();
  }

}
