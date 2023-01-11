const ICONS_DIR = 'build/icons/';

const windowsOS = {
  win: {
    icon: ICONS_DIR + 'win-icon.ico',
    publisherName: 'michal',
    target: 'nsis',
  },

  nsis: {
    differentialPackage: true,
  },
};

const linuxOS = {
  linux: {
    icon: ICONS_DIR + 'linux-icon.png',
    target: 'deb',
  },
};

const macOS = {
  mac: {
    target: 'dmg',
    icon: ICONS_DIR + 'con.icns',
  },
  // add background image to DMG
  dmg: {
    contents: [
      {
        x: 410,
        y: 150,
        type: 'link',
        path: '/Applications',
      },
      {
        x: 130,
        y: 150,
        type: 'file',
      },
    ],
    background: ICONS_DIR + 'dmg-background.png',
  },
};

module.exports = {
  productName: 'QCM scanner',
  appId: 'com.example.app',
  artifactName: 'setup-${version}.${ext}',
  directories: {
    output: 'build',
  },
  // default files: https://www.electron.build/configuration/contents
  files: [
    'package.json',
    {
      from: 'dist/main/',
      to: 'dist/main/',
    },
    {
      from: 'dist/renderer',
      to: 'dist/renderer/',
    },
  ],
  extraResources: [
    {
      from: 'src/extraResources/',
      to: '',
    },
  ],
  ...windowsOS,
  ...linuxOS,
  ...macOS,
};
