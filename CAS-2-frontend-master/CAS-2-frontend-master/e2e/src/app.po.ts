import { browser, by, element } from 'protractor';

export class AppPage {
  navigateTo(): any {
    return browser.get('/auth');
  }

  getParagraphText(): any {
    return element(by.css('app-root .autorize__form-h')).getText();
  }
}
