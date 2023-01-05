import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { MatchesListViewComponent } from './matches-list-view.component';

describe('MatchesListViewComponent', () => {
  let component: MatchesListViewComponent;
  let fixture: ComponentFixture<MatchesListViewComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ MatchesListViewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MatchesListViewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
