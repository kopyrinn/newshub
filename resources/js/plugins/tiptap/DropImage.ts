import { Plugin, PluginKey } from 'prosemirror-state';

export type UploadFn = (image: File) => Promise<string>;

export const dropImagePlugin = (upload: UploadFn) => {
  return new Plugin({
    props: {
      handleDOMEvents: {
        paste(view, event) {
          const items = Array.from(event.clipboardData?.items || []);
          const { schema, selection } = view.state;

          items.forEach((item) => {
            const image = item.getAsFile();

            if (upload && image) {
              upload(image).then((src) => {
                const node = schema.nodes.image.create({
                  src: src,
                });

                const transaction = view.state.tr.replaceSelectionWith(node);
                view.dispatch(transaction);

                const nodeParagraph = schema.nodes.paragraph.create({
                  text: '',
                });
                const position = selection.$head ? selection.$head.pos : selection.$to.pos;
                const transactionParagraph = view.state.tr.insert(position, nodeParagraph);
                view.dispatch(transactionParagraph);
              });
            }
          });

          return false;
        },
        drop: (view, event) => {
          const hasFiles =
            event.dataTransfer &&
            event.dataTransfer.files &&
            event.dataTransfer.files.length;

          if (!hasFiles) {
            return false;
          }

          const images = Array.from(
            event.dataTransfer?.files ?? []
          ).filter((file) => /image/i.test(file.type));

          if (images.length === 0) {
            return false;
          }

          event.preventDefault();

          const { schema, selection } = view.state;
          const coordinates = view.posAtCoords({
            left: event.clientX,
            top: event.clientY,
          });
          if (!coordinates) return false;

          images.forEach(async (image) => {
            const reader = new FileReader();

            if (upload) {
              const node = schema.nodes.image.create({
                src: await upload(image),
              });
              const transaction = view.state.tr.insert(coordinates.pos, node);
              view.dispatch(transaction);

              const nodeParagraph = schema.nodes.paragraph.create({
                text: '',
              });

              const transactionParagraph = view.state.tr.insert(coordinates.pos + 1, nodeParagraph);
              view.dispatch(transactionParagraph);
            } else {
              reader.onload = (readerEvent) => {
                const node = schema.nodes.image.create({
                  src: readerEvent.target?.result,
                });
                const transaction = view.state.tr.insert(coordinates.pos, node);
                view.dispatch(transaction);
              };
              reader.readAsDataURL(image);
            }
          });

          return true;
        },
      },
    },
  });
};