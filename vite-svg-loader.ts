import { promises as fs } from 'fs';
import { compileTemplate } from '@vue/compiler-sfc';
import { optimize as optimizeSvg, Config as SvgoConfig } from 'svgo';
import type { Plugin } from 'vite';

interface SvgLoaderOptions {
    svgoConfig?: SvgoConfig;
    svgo?: boolean;
    defaultImport?: 'component' | 'raw' | 'url';
}

export default function svgLoader(options: SvgLoaderOptions = {}): Plugin {
    const { svgoConfig, svgo = true, defaultImport = 'component' } = options;

    const svgRegex = /\.svg(\?(raw|component|skipsvgo))?$/;

    return {
        name: 'svg-loader',
        enforce: 'pre',

        async load(id: string) {
            if (!svgRegex.test(id)) return;

            const [path, query] = id.split('?', 2);
            const importType = (query as 'raw' | 'component' | 'skipsvgo' | 'url') || defaultImport;

            if (importType === 'url') {
                // Let Vite handle it normally
                return;
            }

            let svg: string;
            try {
                svg = await fs.readFile(path, 'utf-8');
            } catch {
                console.warn(`\n⚠️  ${id} couldn't be loaded by svg-loader, fallback to default loader.`);
                return;
            }

            // If importing raw SVG content
            if (importType === 'raw') {
                return `export default ${JSON.stringify(svg)}`;
            }

            // Optimize with SVGO if enabled
            if (svgo && importType !== 'skipsvgo') {
                svg = optimizeSvg(svg, {
                    ...(svgoConfig || {}),
                    path,
                }).data;
            }

            // Compile SVG into a Vue render function
            const { code } = compileTemplate({
                id: JSON.stringify(id),
                source: svg,
                filename: path,
                transformAssetUrls: false,
            });

            return `${code}\nexport default { render }`;
        },
    };
}
