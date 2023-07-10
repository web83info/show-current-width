"use strict";

class ShowCurrentWidth {

	constructor() {
		this.timeoutWidth = undefined;
		this.timeoutCountUp = undefined;
		this.delay = 500;
		this.widthFrom = Number(window.innerWidth);
		this.widthTo = Number(window.innerWidth);
		this.widthNow = Number(window.innerWidth);
		this.countUpProceeding = false;
		window.addEventListener('load', this.showWidth());
		window.addEventListener('load', this.setTimer());
	}

	showWidth() {
		this.widthTo = window.innerWidth;
		this.countUpProceeding = true;
		this.countUpAnimation(this.widthFrom, this.widthTo);
	}

	setTimer() {
		window.addEventListener('resize', () => {
			clearTimeout(this.timeoutWidth);
			this.timeoutWidth = setTimeout(() => {
				this.showWidth();
			}, this.delay);
		}, false);
	}

	countUpAnimation() {
		let breakPointShort = undefined;
		let breakPointLong = undefined;
		let countUpUnit = Math.abs(this.widthTo - this.widthFrom) / 30;
		W83ShowCurrentWidth.breakpoints_definition.split('\n').forEach(line => {
			let items = line.trim().split(/\s*,\s*/);
			if (items[0] <= this.widthNow && this.widthNow < items[1]) {
				breakPointShort = items[2];
				breakPointLong = items[3];
			}
		});
		document.querySelector('#wp-admin-bar-w83-show-current-width .ab-icon .width').textContent = Math.round(this.widthNow);
		document.querySelector('#wp-admin-bar-w83-show-current-width .ab-label .width').textContent = Math.round(this.widthNow);
		if (1 == W83ShowCurrentWidth.breakpoints_show) {
			document.querySelector('#wp-admin-bar-w83-show-current-width .breakpoint').textContent = breakPointShort;
			document.querySelector('#wp-admin-bar-w83-show-current-width-breakpoint .breakpoint').textContent = breakPointLong;
		}

		if (!this.countUpProceeding) {
			clearTimeout(this.timeoutCountUp);
			this.widthFrom = this.widthTo;
			return;
		}

		if (this.widthNow > this.widthTo) {
			this.widthNow -= countUpUnit;
		} else if (this.widthNow < this.widthTo) {
			this.widthNow += countUpUnit;
		}

		if (Math.abs(this.widthNow - this.widthTo) <= countUpUnit) {
			this.widthNow = this.widthTo;
			this.countUpProceeding = false;
		}

		this.timeoutCountUp = setTimeout(() => {
			this.countUpAnimation();
		}, 7);

	}
}

new ShowCurrentWidth();
