const style = getComputedStyle(document.documentElement);

export const breadkpoints = {};
['xs', 'sm', 'md', 'lg', 'xl'].forEach((bp) => {
    breadkpoints[bp] = parseInt(style.getPropertyValue(`--breakpoint-${bp}`).replace(/[^\d]/uig, ''));
});

console.log(style.getPropertyValue(`--grid-gutter-width`));
export const grid = {
    gutter: parseInt(style.getPropertyValue(`--grid-gutter-width`).replace(/[^\d]/uig, ''))
};

