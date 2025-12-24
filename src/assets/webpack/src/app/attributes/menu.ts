import {customAttribute, ILogger, INode, resolve, IPlatform, IEventAggregator} from "aurelia";
import {ApiServices} from "../services/api-services";

@customAttribute('fractal-cms-core-menu')
export class Menu {

    private actionButtons:NodeList;
    public constructor(
        private readonly logger: ILogger = resolve(ILogger).scopeTo('Menu'),
        private readonly element: HTMLElement = resolve(INode) as HTMLElement,
        private readonly ea: IEventAggregator = resolve(IEventAggregator),
        private readonly apiService: ApiServices = resolve(ApiServices),
        private readonly platform:IPlatform = resolve(IPlatform)
    ) {
        this.logger.trace('constructor');
    }

    public attached()
    {
        this.logger.trace('attached');
        this.addEvent();
    }
    public detached()
    {
        this.logger.trace('attached');
        this.removeEvent();
    }

    private addEvent()
    {
        this.logger.trace('addEvent');
        this.actionButtons = this.element.querySelectorAll('[aria-controls]');
       this.actionButtons.forEach((ele, index) => {
           ele.addEventListener('click', this.onAction);
       });
    }

    private removeEvent()
    {
        this.logger.trace('removeEvent');
        this.actionButtons.forEach((ele, index) => {
            ele.removeEventListener('click', this.onAction);
        });

    }
    private readonly onAction = (event:Event) => {
        this.logger.trace('onAction');
        let target:HTMLElement = <HTMLElement>event.target;
        if (target) {
            if (target.nodeName !== 'button') {
                target = target.closest('button');
            }
            const idControls:string = target.getAttribute('aria-controls');
            if (idControls) {
                this.closeOthers(idControls);
                const nav:HTMLElement = this.element.querySelector('#'+idControls);
                if (nav) {
                    if (nav.classList.contains('is-open')) {
                        nav.classList.remove('is-open');
                        nav.setAttribute('aria-expanded', 'false');
                        target.setAttribute('aria-expanded', 'false');
                    } else {
                        nav.classList.add('is-open');
                        nav.setAttribute('aria-expanded', 'true');
                        target.setAttribute('aria-expanded', 'true');
                    }
                }
            }
        }
    }

    private closeOthers(id:string)
    {
        this.logger.trace('closeOthers');
        const dropdownMenu:NodeListOf<HTMLElement> = this.element.querySelectorAll('.fractal-submenu');
        dropdownMenu.forEach((element:HTMLElement, key) => {
            if (element.id != id) {
                element.classList.remove('is-open');
            }
        });
    }
}